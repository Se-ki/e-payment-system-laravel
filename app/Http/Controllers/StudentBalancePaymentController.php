<?php

namespace App\Http\Controllers;

use App\Models\StudentBalancePayment;
use Illuminate\Support\Facades\Auth;
use App\Helper\PS;
use App\Models\LoginUser;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request as HttpRequest;

class StudentBalancePaymentController extends Controller {
    public function index() {
        $balances = StudentBalancePayment::latest("sbp_date_paid")->where('student_id', Auth::user()->student->id)->get();
        return view('balance.index', [
            'header' => 'Student Balance Payments',
            'balances' => $balances,
        ]);
    }
    public function create(Payment $payment) {
        if(PS::checkIfCollectorOrAdmin()) {
            return redirect('/');
        }
        return view('balance.create', ['payment' => $payment]);
    }

    public function show(LoginUser $student) {
        $user = $student->student;
        if(PS::checkIfCollectorOrAdmin()) {
            return redirect('/');
        }
        return view('balance.show', [
            'user' => $student,
            'header' => "Balance of $user->lastname",
            'balances' => StudentBalancePayment::latest()->where('student_id', $student->id)->get()
        ]);
    }

    public function store(Payment $payment, HttpRequest $request) {
        $uniqueIdentifier = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $currentDate = now()->format('Ymd');
        $generatedCode = $currentDate.$uniqueIdentifier;
        $balance = new StudentBalancePayment([
            'academic_year_id' => $payment->academic_year_id,
            'sbp_description' => $payment->description->name,
            'sbp_receipt_number' => $generatedCode,
            'sbp_amount' => $request->sbp_amount,
            'sbp_paid_amount' => $request->sbp_paid_amount,
            'sbp_paid_change' => $request->sbp_paid_change,
            'sbp_balance_amount' => $request->sbp_balance_amount,
            'sbp_semester' => $payment->p_semester,
            'sbp_date_paid' => NOW(),
            'status' => $request->status,
            'encoder' => Auth::user()->student->firstname." ".Auth::user()->student->middlename." ".Auth::user()->student->lastname,
        ]);
        $user = Student::find($payment->student_id);

        $user->balance()->save($balance);

        PaymentController::destroy($payment->id);

        return redirect()->back();
    }


    public function edit(StudentBalancePayment $balance) {
        return view('balance.edit', [
            'balance' => $balance,
            'student' => $balance->student,
        ]);
    }

    public function update(StudentBalancePayment $balance, HttpRequest $request) {
        // dd($request->all());
        $student = LoginUser::find($balance->student_id);
        StudentBalancePayment::find($balance->id)->update($request->all());
        return redirect(route('balance.show', $student->username));
    }

    public function listOfStudent() {
        if(PS::checkIfCollectorOrAdmin()) {
            return redirect('/');
        }
        return view('balance.student.index', [
            'header' => 'List of Students',
            'users' => LoginUser::where('role_type_id', 1)->get(),
        ]);
    }
    public function listOfPayments(LoginUser $student) {
        $user = $student->student;
        if(PS::checkIfCollectorOrAdmin()) {
            return redirect('/');
        }
        return view('balance.student.payment.index', [
            'header' => "Payments of $user->firstname",
            'payments' => Payment::latest()->where('student_id', $student->id)->get()
        ]);
    }
}

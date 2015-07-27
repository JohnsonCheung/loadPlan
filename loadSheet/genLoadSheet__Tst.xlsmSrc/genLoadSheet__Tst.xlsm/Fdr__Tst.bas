Attribute VB_Name = "Fdr__Tst"
Option Explicit
Sub TstAll_Fdr()
MovToEr__Tst
NewFdr__Tst
Gen__Tst
End Sub

Private Sub MovToEr__Tst()
CpyQue
Dim Ay$()
    Ay = LSApp.QueSegAy
    Debug.Assert Sz(Ay) = 2
    Debug.Assert Ay(0) = "Trip-2015-01-01#001"
    Debug.Assert Ay(1) = "Trip-2015-01-01#002"
    
PthDlt_Fdr LSPth.QueErr

Dim FdrAy$()
    FdrAy = PthAyFdr(LSPth.QueErr)
    
Debug.Assert Sz(FdrAy) = 0

Dim J%, A$
For J = 0 To UB(Ay)
    A = NewFdr(Ay(J)).MovToEr
    Debug.Assert IsPth(A)
Next
CpyQue
Pass "MovToEr__Tst"
End Sub

Private Sub NewFdr__Tst()
Dim M As Fdr
    
'--
    Set M = NewFdr(C_Seg1)
    Debug.Assert M.PthInp = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\" & C_Seg1 & "\"

'--
    Set M = NewFdr(C_Seg2)
    Debug.Assert M.PthInp = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\" & C_Seg2 & "\"

Pass "NewFdr__Tst"
End Sub

Private Sub Gen__Tst()
CpyQue

Dim N%
    N = Sz(PthAyFdr(LSPth.QueErr))

Dim Ay$()
    Ay = PthAyFdr(LSPth.Que)
    Debug.Assert Sz(Ay) = 2
    Debug.Assert Ay(0) = C_Seg1
    Debug.Assert Ay(1) = C_Seg2

NewFdr(C_Seg1).Gen
    Ay = PthAyFdr(LSPth.Que)
    Debug.Assert Sz(Ay) = 1
    Debug.Assert Ay(0) = C_Seg2

Debug.Assert N = Sz(PthAyFdr(LSPth.QueErr))

NewFdr(C_Seg2).Gen
    Ay = PthAyFdr(LSPth.Que)
    Debug.Assert Sz(Ay) = 0

Debug.Assert N = Sz(PthAyFdr(LSPth.QueErr))

CpyQue
Pass "Gen__Tst"
End Sub


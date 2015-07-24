VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "RmkOfOrd"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private A_RmkLinAy() As RmkLinOfOrd

Sub Init(Fdr$)
Dim Ay$()
    Dim P$, F$
    P = Pth.Que & Fdr & "\"
    F = P & "rmk_OfAdr.Txt"
    If Fso.FileExists(F) Then Ay = FtAy(F)

Dim J%
For J = 0 To UB(Ay)
    Dim M As New RmkLinOfAdr
    M.ReadLin Ay(J)
    PushObj A_RmkLinAy, M
Next
End Sub

Property Get NLin%()
NLin = Sz(A_RmkLinAy)
End Property

Function FillWs(Ws As Worksheet, ByRef ORno&) As Boolean
Dim Step$
Const C = "@地址送貨指示"

Step = "1. Fill Tit"
    Dim A$
    If NLin = 0 Then A = "沒有 "
    Ws.Range("A" & ORno).Value = A & C
    ORno = ORno + 2

If NLin = 0 Then Exit Function

Step = "2 Fill Each Line"
    Dim J%
    For J = 0 To NLin - 1
        A_RmkLinAy(J).FillLin Ws, ORno, J
        ORno = ORno + J + 1
    Next
Exit Function
Er:
    WrtLog vbTab & "Err in fill remark. ErMsg=[" & Err.Description & "] Step=[" & Step & "]"
    FillWs = True
End Function

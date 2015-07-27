VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "RmkOfOrd"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Private A_RmkLinAy() As RmkLinOfOrd
Private A_Seg$

Function NewRmkOfOrd(Seg$) As RmkOfOrd
A_Seg = Seg
Dim Ay$()
    If Fso.FileExists(Ft) Then Ay = FtAy(Ft)

Dim J%
For J = 0 To UB(Ay)
    PushObj A_RmkLinAy, NewRmkLinOfOrd(Ay(J))
Next
Set NewRmkOfOrd = Me
End Function

Property Get Ft$()
Dim P$, F$
    P = LSPth.Que & A_Seg & "\"
    Ft = P & "rmk_OfOrd.Txt"
End Property

Property Get NRmk%()
NRmk = Sz(A_RmkLinAy)
End Property

Sub FillWs(Ws As Worksheet, ByRef ORno&)
Dim Step$
Const C = "*柯打指示"
If NRmk = 0 Then
    Ws.Range("A" & ORno).Value = "沒有 " & C
    ORno = ORno + 2
    Exit Sub
End If

Ws.Range("A" & ORno).Value = C
ORno = ORno + 1

Dim J%
For J = 0 To NRmk - 1
    A_RmkLinAy(J).FillLin Ws, ORno, J
    ORno = ORno + 1
Next
ORno = ORno + 1
End Sub

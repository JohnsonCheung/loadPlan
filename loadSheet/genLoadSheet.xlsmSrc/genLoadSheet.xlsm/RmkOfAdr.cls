VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "RmkOfAdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Private A_RmkLinAy() As RmkLinOfAdr
Private A_Seg$

Function NewRmkOfAdr(Seg$) As RmkOfAdr
A_Seg = Seg
Dim Ay$()
    If Fso.FileExists(Ft) Then Ay = FtAy(Ft)

Dim J%
For J = 0 To UB(Ay)
    PushObj A_RmkLinAy, NewRmkLinOfAdr(Ay(J))
Next
Set NewRmkOfAdr = Me
End Function

Property Get Ft$()
Dim P$
    P = LSPth.Que & A_Seg & "\"
    Ft = LSPth.Que & A_Seg & "\" & "rmk_OfAdr.Txt"
End Property

Property Get NRmk%()
NRmk = Sz(A_RmkLinAy)
End Property

Sub FillWs(Ws As Worksheet, ByRef ORno&)
Const C = "@地址指示"
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

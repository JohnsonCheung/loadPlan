Attribute VB_Name = "Drop__Tst"
Option Explicit
Const Inspect = True
Sub TstAll_Drop()
NewDrop__Tst
FillWs__Tst
End Sub

Private Sub NewDrop__Tst()
CpyQue2
Dim M As Drop
Set M = NewDrop(C_Seg2)
Pass "NewDrop__Tst"
End Sub

Private Sub FillWs__Tst()
CpyQue2
Dim Ws As Worksheet
Dim Rno&
Dim M As Drop
    Rno = 12
    Set Ws = NewWs1(C_Seg2).AddWs1(WbNew)
    Set M = NewDrop(C_Seg2)

M.FillWs Ws, Rno
If Inspect Then
    Application.Visible = True
    Stop
End If
WsWb(Ws).Close False
Pass "FillWs__Tst"
End Sub

Attribute VB_Name = "Ws1__Tst"
Option Explicit
Const Inspect = True

Sub AAA()
FillWs__Tst
End Sub

Sub TstAll_Ws1()
NewWs1__Tst
FillWs__Tst
End Sub

Private Sub FillWs__Tst()
CpyQue1
Dim Wb As Workbook
    Set Wb = WbNew
NewWs1(C_Seg1).FillWs Wb
If Inspect Then
    Application.Visible = True
    Stop
End If
Wb.Close False
Pass "FillWs__Tst"
End Sub

Private Sub NewWs1__Tst()
CpyQue1
Dim M As Ws1
Set M = NewWs1(C_Seg1)
Debug.Assert M.Name = "¸ü³f¯È"
Pass "NewWs1__Tst"
End Sub

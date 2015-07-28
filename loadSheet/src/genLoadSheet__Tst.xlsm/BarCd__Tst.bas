Attribute VB_Name = "BarCd__Tst"
Option Explicit
'Const Inspect = True
Sub TstAll_BarCd()
Debug.Print "TstAll_BarCd -----------------"
NewBarCd__Tst
PutBarCd__Tst
End Sub

Private Sub PutBarCd__Tst()
CpyQue2
Dim Ws As Worksheet
    Set Ws = NewWs1(C_Seg2).AddWs1(WbNew)

NewBarCd(C_Seg1).PutBarCd Ws
If Inspect Then
    Ws.Application.Visible = True
    Stop
End If
WsWb(Ws).Close False
Pass "PutBarCd__Tst"
End Sub

Private Sub NewBarCd__Tst()
Dim M As BarCd
    Set M = NewBarCd(C_Seg1)
Debug.Assert M.A_BarCdFfn = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\BarCd.png"
Pass "NewBarCd__Tst"
End Sub


Attribute VB_Name = "BarCd__Tst"
Option Explicit
Sub TstAll_BarCd()
NewBarCd__Tst
PutBarCd__Tst
End Sub

Private Sub PutBarCd__Tst()
Dim Ws As Worksheet, Wb As Workbook
Set Ws = WsNew
Set Wb = Ws.Parent
WsLoadSheet.Copy Ws
Set Ws = Wb.Sheets(1)

NewBarCd(C_Seg1).PutBarCd Ws
Ws.Application.Visible = True
Logr.Brw
Pass "PutBarCd__Tst"
End Sub

Private Sub NewBarCd__Tst()
Dim M As BarCd
    Set M = NewBarCd(C_Seg1)
Debug.Assert M.A_BarCdFfn = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\BarCd.png"
Pass "NewBarCd__Tst"
End Sub


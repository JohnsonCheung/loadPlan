VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Ws1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Attribute VB_Description = "Loading Sheet Ws1"
Option Explicit
Public A_Ws As Worksheet
Public Hdr As New Hdr
Public Rmk As New Rmk
Public Drop As New Drop
Public BarCd As New BarCd

Sub Init(Fdr$)
Drop.Init Fdr
Hdr.Init Fdr
Rmk.Init Fdr
BarCd.Init Fdr
End Sub

Function Name$()
Name = WsLoadSheet.Name
End Function

Function AddWs(Wb As Workbook) As Worksheet
WsLoadSheet.Copy Wb.Sheets(1)
Set A_Ws = Wb.Sheets(1)
Set AddWs = A_Ws
End Function

Function FillWs(Wb As Workbook) As Boolean
Wb.Application.ScreenUpdating = False
Dim AA As Boolean
AA = Application.DisplayAlerts
Application.DisplayAlerts = False ' Because there is invisible Wb-names: LOCAL_DATE_SEPARATOR, ...
                                  ' When copying two worksheet the Wb-names will exist
Set A_Ws = AddWs(Wb)
Dim Ws As Worksheet
Set Ws = A_Ws

Application.DisplayAlerts = AA

If Hdr.FillWs(Ws) Then FillWs = True       '<-- FillHdr
If BarCd.PutBarCd(Ws) Then FillWs = True           '<-- PutBarCd
Dim Rno%
Rno = 11
If Drop.FillWs(Ws, Rno) Then FillWs = True
If Rmk.FillWs(Ws, Rno + 1) Then FillWs = True  '<-- FillRmk

Dim C1%, R1%, C2, R2%

Dim PrtAreaRge As Range
    C1 = 1
    R1 = 11
    C2 = Ws1LastCol
    R2 = 11 + Drop.NDrop + (Rmk.NLin) + 1
    Set PrtAreaRge = Ws.Range(Ws.Cells(R1, C1), Ws.Cells(R2, C2))

PrtAreaRge.Worksheet.PageSetup.PrintArea = PrtAreaRge.Address   '<== Set print area

Dim A$, B$
    A = "生成: " & Format(Now(), "YYYY-MM-DD HH:MM:SS")
    B = "行程: " & Hdr.TripChiNm
Ws.PageSetup.LeftFooter = A     '<== Set Footer
Ws.PageSetup.RightFooter = B    '<== Set Footer
End Function

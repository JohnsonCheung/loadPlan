VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "FillWs1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Attribute VB_Description = "Loading Sheet Ws1"
Option Explicit
Public A_Ws As Worksheet
Public FillHdr As New FillHdr
Public FillRmk As New FillRmk
Public FillDrops As New FillDrops
Public FillBarCd As New FillBarCd

Sub Init(Fdr$)
'Copy WsTp & WsAtt to a new workbook
FillDrops.Init Fdr
FillHdr.Init Fdr
FillRmk.Init Fdr
FillBarCd.Init Fdr
End Sub

Function Name$()
Name = WsLoadSheet.Name
End Function

Function Fillin(Wb As Workbook) As Boolean
Wb.Application.ScreenUpdating = False
Dim AA As Boolean
AA = Application.DisplayAlerts
Application.DisplayAlerts = False ' Because there is invisible Wb-names: LOCAL_DATE_SEPARATOR, ...
                                  ' When copying two worksheet the Wb-names will exist
WsLoadSheet.Copy Wb.Sheets(1)
Set A_Ws = Wb.Sheets(1)
Dim Ws As Worksheet
Set Ws = A_Ws

Application.DisplayAlerts = AA

If FillHdr.Fillin(Ws) Then Fillin = True       '<-- FillHdr
If FillBarCd.PutBarCd(Ws) Then Fillin = True           '<-- PutBarCd
Dim Rno%
Rno = 11
If FillDrops.Fillin(Ws, Rno) Then Fillin = True
If FillRmk.FillRmk(Ws, Rno + 1) Then Fillin = True  '<-- FillRmk

Dim C1%, R1%, C2, R2%

Dim PrtAreaRge As Range
    C1 = 1
    R1 = 11
    C2 = Ws1LastCol
    R2 = 11 + FillDrops.NDrop + (FillRmk.NLin) + 1
    Set PrtAreaRge = Ws.Range(Ws.Cells(R1, C1), Ws.Cells(R2, C2))

PrtAreaRge.Worksheet.PageSetup.PrintArea = PrtAreaRge.Address   '<== Set print area

Dim A$, B$
    A = "生成: " & Format(Now(), "YYYY-MM-DD HH:MM:SS")
    B = "行程: " & FillHdr.TripChiNm
Ws.PageSetup.LeftFooter = A     '<== Set Footer
Ws.PageSetup.RightFooter = B    '<== Set Footer
End Function

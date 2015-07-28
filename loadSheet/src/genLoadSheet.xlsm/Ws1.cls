VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Ws1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Attribute VB_Description = "Loading Sheet Ws1"
Option Explicit
Public A_Ws As Worksheet
Public Hdr As Hdr
Public Rmk As Rmk
Public Drop As Drop
Public BarCd As BarCd

Function NewWs1(Seg$) As Ws1
Set Drop = NewDrop(Seg)
Set Hdr = NewHdr(Seg)
Set Rmk = NewRmk(Seg)
Set BarCd = NewBarCd(Seg)
Set NewWs1 = Me
End Function

Function Name$()
Name = WsLoadSheet.Name
End Function

Function AddWs1(Wb As Workbook) As Worksheet
WsLoadSheet.Copy Wb.Sheets(1)
Set A_Ws = Wb.Sheets(1)
Set AddWs1 = A_Ws
End Function

Sub FillWs(Wb As Workbook)
Wb.Application.ScreenUpdating = False
Dim AA As Boolean
AA = Application.DisplayAlerts
Application.DisplayAlerts = False ' Because there is invisible Wb-names: LOCAL_DATE_SEPARATOR, ...
                                  ' When copying two worksheet the Wb-names will exist
Set A_Ws = AddWs1(Wb)
Dim Ws As Worksheet
    Set Ws = A_Ws

Application.DisplayAlerts = AA

Dim Rno&
    Rno = 12

Hdr.FillWs Ws      '<-- Hdr
BarCd.PutBarCd Ws  '<-- PutBarCd
Drop.FillWs Ws, Rno
Rmk.FillWs Ws, Rno + 1 '<-- Rmk

Dim PrtAreaRge As Range
    Dim C1%, R1%, C2, R2%
    C1 = 1
    R1 = 12
    C2 = Ws1LastCol
    R2 = 12 + Drop.NDrop + (Rmk.NLin)
    Set PrtAreaRge = WsRCRC(Ws, R1, C1, R2, C2)

PrtAreaRge.Worksheet.PageSetup.PrintArea = PrtAreaRge.Address   '<== Set print area

Dim A$, B$
    A = "生成: " & Format(Now(), "YYYY-MM-DD HH:MM:SS")
    B = "行程: " & Hdr.TripChiNm
Ws.PageSetup.LeftFooter = A     '<== Set Footer
Ws.PageSetup.RightFooter = B    '<== Set Footer

Ws.Range("A1").Select
Ws.Range("A1").Activate
Ws.Range("A1").Show
Ws.Range("E2").Select
End Sub

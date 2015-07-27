Attribute VB_Name = "Hdr__Tst"
Option Explicit
Private Hdr As Hdr
Sub TstAll_Hdr()
Debug.Print "TstAll_Hdr ------------------------------"
NewHdr__Tst
FillWs__Tst
End Sub
Private Sub NewHdr__Tst()
CpyQue1
Set Hdr = NewHdr(C_Seg1)
Debug.Assert Hdr.Driver = "Driver1"
Debug.Assert Hdr.DriverTy = "DriverTy1"
'Debug.Assert Hdr.A_Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\hdr.txt"
Debug.Assert Hdr.Member = "Member1"
Debug.Assert Hdr.Leader = "Leader1"
Debug.Assert Hdr.TripChiNm = "2015-02-01-Trip-001"

Pass "NewHdr__Tst"
End Sub

Private Sub FillWs__Tst()
Dim Ws As Worksheet, Wb As Workbook
    Set Wb = WbNew
    Set Ws = WsNew

NewHdr(C_Seg1).FillWs Ws
Stop
Wb.Close False
Pass "FillWs__Tst"
End Sub

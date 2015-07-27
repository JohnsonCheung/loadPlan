Attribute VB_Name = "Hdr__Tst"
Option Explicit
Private Hdr As Hdr
'Const Inspect = True
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
Debug.Assert Hdr.A_Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\Hdr.txt"
Debug.Assert Hdr.Member = "Member1"
Debug.Assert Hdr.Leader = "Leader1"
Debug.Assert Hdr.TripChiNm = "2015-02-01-Trip-001"
Pass "NewHdr__Tst"
End Sub

Private Sub FillWs__Tst()
Dim Ws As Worksheet
    Dim Ws1 As Ws1, Wb As Workbook
    Set Wb = WbNew
    Set Ws1 = NewWs1(C_Seg1)
    Set Ws = Ws1.AddWs1(Wb)

NewHdr(C_Seg1).FillWs Ws
If Inspect Then
    Wb.Application.Visible = True
    Stop
End If
Wb.Close False
Pass "FillWs__Tst"
End Sub

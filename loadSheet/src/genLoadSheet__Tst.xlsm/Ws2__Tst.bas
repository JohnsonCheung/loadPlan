Attribute VB_Name = "Ws2__Tst"
Option Explicit
Const Inspect = True
Sub TstAll_Ws2()
NewWs2__Tst
FillWs__Tst
rPutOneAtt__Tst
End Sub

Private Sub FillWs__Tst()
CpyQue1
CpyQue2
Dim Wb1 As Workbook
Dim Wb2 As Workbook
    Set Wb1 = WbNew
    Set Wb2 = WbNew

NewWs2(C_Seg2).FillWs Wb2
NewWs2(C_Seg1).FillWs Wb1
If Inspect Then
    Application.Visible = True
    Stop
End If
Wb1.Close False
Wb2.Close False
Pass "FillWs__Tst"
End Sub

Private Sub NewWs2__Tst()
CpyQue1
Dim M As Ws2
    Set M = NewWs2(C_Seg1)

Dim Ay$()
    Ay = M.AttFnAy

Debug.Assert Sz(Ay) = 2
Debug.Assert Ay(0) = "Trip-2015-01-01#001 att-01 (ord-2015-01-01#1234 content-01).png"
Debug.Assert Ay(1) = "Trip-2015-01-01#001 att-02 (ord-2015-01-01#1234 content-02).png"

Debug.Assert M.Name = "ªþ­¶"
Pass "Tst_Ws2_Init"
End Sub

Private Sub rPutOneAtt__Tst()
Dim AttFn$, Cus$, Content$
Dim Rno%
Dim M As Ws2

    Set M = NewWs2(C_Seg1)
    Rno = 2
    AttFn = "Trip-2015-01-01#001 Att-02 (Ord-2015-01-01#1234 Content-02).png"
    Cus = "Cus1"
    Content = "aaa\nbbb\nccc"

Dim Ws As Worksheet
Dim Wb As Workbook
    Set Wb = WbNew
    Set Ws = WbNewWs(Wb, AtEnd:=True)

M.rPutOneAtt Ws, Rno, Cus, Content, AttFn
Debug.Assert Rno = 19
If Inspect Then
    Application.Visible = True
    Stop
End If
Wb.Close False
Pass "rPutOneAtt__Tst"
End Sub

Attribute VB_Name = "Ws2__Tst"
Option Explicit
Sub TstAll_Ws2()
NewWs2__Tst
End Sub

Private Sub FillWs__Tst()
Dim Wb As Workbook
Application.ScreenUpdating = False
Set Wb = WbNew

NewWs2(C_Seg1).FillWs Wb
Stop
Wb.Close False
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
CpyQue
Pass "Tst_Ws2_Init"
End Sub

Private Sub rPutOneAtt__Tst()
Dim AttFn$, CusCd$, ContentRmk$
Dim Rno%
Dim M As Ws2

Set M = NewWs2(C_Seg1)

Rno = 2
AttFn = "Trip-2015-01-01#001 Att-02 (Ord-2015-01-01#1234 Content-02).png"

Dim Ws As Worksheet
Dim Wb As Workbook
    Set Wb = WbNew
    Set Ws = WbNewWs(Wb, AtEnd:=True)

M.rPutOneAtt Ws, Rno, CusCd, ContentRmk, AttFn
Debug.Assert Rno = 7
Pass "Tst_PutOneAtt"
End Sub

Attribute VB_Name = "RmkOfAdr__Tst"
Option Explicit
Dim M As RmkOfAdr

Sub TstAll_RmkOfAdr()
Debug.Print "TstAll_RmkOfAdr ------------------------"
NewRmkOfAdr__Tst
FillLin__Tst
NRmk__Tst
Ft__Tst
End Sub

Private Sub NewRmkOfAdr__Tst()
Set M = NewRmkOfAdr(C_Seg1)
Pass "NewRmkOfAdr__Tst"
End Sub

Private Sub Ft__Tst()
Set M = NewRmkOfAdr(C_Seg1)
Debug.Assert M.Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\rmk_OfAdr.Txt"
End Sub

Private Sub FillLin__Tst()
Dim Ws1 As Worksheet
Dim Ws2 As Worksheet
    Set Ws1 = WsNew
    Set Ws2 = WbNewWs(WsWb(Ws1))
    
Dim Rno1&, Rno2&
    Rno1 = 1
    Rno2 = 1

NewRmkOfAdr(C_Seg1).FillWs Ws1, Rno1
NewRmkOfAdr(C_Seg2).FillWs Ws2, Rno2
Debug.Assert Rno1 = 6
Debug.Assert Rno2 = 3

If Inspect Then
    Ws1.Application.Visible = True
    Stop
End If
WsCls Ws1
Pass "FillLin__Tst"
End Sub

Private Sub NRmk__Tst()
CpyQue1
CpyQue2
Debug.Assert NewRmkOfAdr(C_Seg1).NRmk = 3
Debug.Assert NewRmkOfAdr(C_Seg2).NRmk = 0
End Sub



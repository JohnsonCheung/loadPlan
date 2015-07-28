Attribute VB_Name = "RmkOfOrd__Tst"
Option Explicit
Dim M As RmkOfOrd
'Private Const Inspect = True

Sub TstAll_RmkOfOrd()
Debug.Print "TstAll_RmkOfOrd ------------------------"
NewRmkOfOrd__Tst
FillLin__Tst
NRmk__Tst
Ft__Tst
End Sub

Private Sub NewRmkOfOrd__Tst()
Set M = NewRmkOfOrd(C_Seg1)
Pass "NewRmkOfOrd__Tst"
End Sub

Private Sub Ft__Tst()
Set M = NewRmkOfOrd(C_Seg1)
Debug.Assert M.Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\rmk_OfOrd.Txt"
End Sub

Private Sub FillLin__Tst()
CpyQue
Dim Ws1 As Worksheet
Dim Ws2 As Worksheet
    Set Ws1 = WsNew
    Set Ws2 = WbNewWs(WsWb(Ws1))
    
Dim Rno1&, Rno2&
    Rno1 = 1
    Rno2 = 1
NewRmkOfOrd(C_Seg1).FillWs Ws1, Rno1
NewRmkOfOrd(C_Seg2).FillWs Ws2, Rno2

Debug.Assert Rno1 = 6
Debug.Assert Rno2 = 7
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
Debug.Assert NewRmkOfOrd(C_Seg1).NRmk = 3
Debug.Assert NewRmkOfOrd(C_Seg2).NRmk = 4
End Sub




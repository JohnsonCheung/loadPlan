Attribute VB_Name = "RmkLinOfAdr__Tst"
Option Explicit
Dim M As RmkLinOfAdr
Private Const Inspect As Boolean = True
Sub TstAll_RmkLinOfAdr()
Debug.Print "TstAll_RmkLinOfAdr ------------------------"
NewRmkLinOfAdr__Tst
FillLin__Tst
NLin__Tst
End Sub

Private Sub NewRmkLinOfAdr__Tst()
Set M = NewRmkLinOfAdr("AA,BB,CC\nDD")
Debug.Assert M.Cus = "AA"
Debug.Assert M.Adr = "BB"
Debug.Assert M.Rmk = "CC\nDD"
Pass "NewRmkLinOfAdr__Tst"
End Sub

Private Sub FillLin__Tst()
Dim Ws As Worksheet
    Set Ws = WsNew

Set M = NewRmkLinOfAdr("AA,BB,CC\nDD")
M.FillLin Ws, 1, 1
If Inspect Then
    Ws.Application.Visible = True
    Stop
End If
WsCls Ws
Pass "FillLin__Tst"
End Sub

Private Sub NLin__Tst()
Debug.Assert NewRmkLinOfAdr("AA,BB,CC\nDD\nEE").NLin = 3
Debug.Assert NewRmkLinOfAdr("AA,BB,CC\nDD").NLin = 2
End Sub


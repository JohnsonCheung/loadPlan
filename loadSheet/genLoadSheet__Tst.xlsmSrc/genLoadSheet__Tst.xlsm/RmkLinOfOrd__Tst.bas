Attribute VB_Name = "RmkLinOfOrd__Tst"
Option Explicit
Dim M As RmkLinOfOrd
Sub TstAll_RmkLinOfOrd()
Debug.Print "TstAll_RmkLinOfOrd ------------------------"
NewRmkLinOfOrd__Tst
FillLin__Tst
NLin__Tst
End Sub

Private Sub NewRmkLinOfOrd__Tst()
Set M = NewRmkLinOfOrd("AA,CC\nDD")
Debug.Assert M.Cus = "AA"
Debug.Assert M.Rmk = "CC\nDD"
Pass "NewRmkLinOfOrd__Tst"
End Sub

Private Sub FillLin__Tst()
Dim Ws As Worksheet
    Set Ws = WsNew

Set M = NewRmkLinOfOrd("AA,CC\nDD")
M.FillLin Ws, 1, 1
If Inspect Then
    Ws.Application.Visible = True
    Stop
End If
WsCls Ws
Pass "FillLin__Tst"
End Sub

Private Sub NLin__Tst()
Debug.Assert NewRmkLinOfOrd("AA,CC\nDD\nEE").NLin = 3
Debug.Assert NewRmkLinOfOrd("AA,CC\nDD").NLin = 2
End Sub

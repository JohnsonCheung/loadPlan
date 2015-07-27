Attribute VB_Name = "XlsWb__Tst"
Option Explicit

Sub WbWsNmAy__Tst()
Dim Act$()
Act = WbWsNmAy(ThisWorkbook)
Debug.Assert Sz(Act) = 1
Debug.Assert Act(0) = "Sheet1"
End Sub

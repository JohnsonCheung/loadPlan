Attribute VB_Name = "VbPth__Tst"
Option Explicit

Sub TstAll_VbPth()
PthAyFnLik__Tst
PthDlt__Tst
End Sub

Private Sub PthAyFnLik__Tst()
Dim Act$()
Act = PthAyFnLik("C:\", "Pytho##")
Debug.Assert Sz(Act) = 2
Debug.Assert Act(0) = "Python27"
Debug.Assert Act(0) = "Python33"
End Sub

Private Sub PthDlt__Tst()
Const Pth = "C:\Temp\AA"
MkDir Pth
Open Pth & "\A.txt" For Output As #1
Print #1, "aa"
Print #1, "aa"
Print #1, "aa"
Print #1, "aa"
Close #1
Dim F%
F = FreeFile(1)
Dim L$
Open "C:\Temp\AA\A.txt" For Input As #F
While Not EOF(F)
    Line Input #F, L
Wend
Close #F
PthDlt Pth, Force:=True
End Sub

Attribute VB_Name = "LSPth__Tst"
Option Explicit
Sub TstAll_LSPth()
Debug.Print "TstAll_LSPth -----------------"
QueErr__Tst
QueTst__Tst
Que__Tst
Hom__Tst
End Sub

Private Sub QueErr__Tst()
Debug.Assert LSPth.QueErr = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\QueErr\"
Pass "QueErr__Tst"
End Sub

Private Sub Que__Tst()
Debug.Assert LSPth.Que = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\"
Pass "Que__Tst"
End Sub

Private Sub QueTst__Tst()
Debug.Assert LSPth.QueTst = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\QueTst\"
Pass "QueTst__Tst"
End Sub

Private Sub Hom__Tst()
Debug.Assert LSPth.Hom = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\"
Pass "Hom__Tst"
End Sub

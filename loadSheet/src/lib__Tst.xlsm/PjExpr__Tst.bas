Attribute VB_Name = "PjExpr__Tst"
Option Explicit

Sub TstAll_PjExpr()
Pth__Tst
End Sub

Private Sub Pth__Tst()
Dim M As PjExpr
Set M = NewPjExpr(Pj("jjLib"))
Dim Act$, Exp$
    Act = M.Pth
    Exp = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Src\lib.xlam\"
Debug.Assert Act = Exp
Pass "Pth__Tst"
End Sub

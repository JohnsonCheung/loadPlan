Attribute VB_Name = "LSFfn__Tst"
Option Explicit

Sub TstAll_LSFfn()
Content__Tst
End Sub
Private Sub Content__Tst()
Debug.Assert LSFfn.Content("150101", 1, 1) = "C:\xampp\htdocs\loadPlan\ordContent\2015\01\01\0001\Ord-20150101-0001-01.png"
Pass "Content__Tst"
End Sub

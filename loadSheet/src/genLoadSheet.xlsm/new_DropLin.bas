Attribute VB_Name = "new_DropLin"
Option Explicit

Function NewDropLin(Seg$, Fn$) As DropLin
Dim O As New DropLin
Set NewDropLin = O.NewDropLin(Seg, Fn)
End Function

Attribute VB_Name = "new_Pj"
Option Explicit

Function NewPj(VbPj As VBProject) As Pj
Dim O As New Pj
Set NewPj = O.NewPj(VbPj)
End Function

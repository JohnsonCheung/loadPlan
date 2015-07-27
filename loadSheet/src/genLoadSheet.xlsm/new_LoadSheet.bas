Attribute VB_Name = "new_LoadSheet"
Option Explicit

Function NewLoadSheet(Seg$) As LoadSheet
Dim O As New LoadSheet
Set NewLoadSheet = O.NewLoadSheet(Seg)
End Function

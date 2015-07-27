Attribute VB_Name = "new_Drop"
Option Explicit

Function NewDrop(Seg$) As Drop
Dim O As New Drop
Set NewDrop = O.NewDrop(Seg)
End Function

Attribute VB_Name = "new_BarCd"
Option Explicit

Function NewBarCd(Seg$) As BarCd
Dim O As New BarCd
Set NewBarCd = O.NewBarCd(Seg)
End Function

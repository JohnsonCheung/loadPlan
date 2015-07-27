Attribute VB_Name = "XlsShp"
Option Explicit


Function ShpNxtRno&(Shp As Shape, Rge As Range)
'Return the next Rno after the shape
Dim H!, R As Range, J%
H = Shp.Height
For J = 1 To 10000 ' Assume max 10000 rows
    Set R = Rge(J, 1)
    H = H - R.RowHeight
    If H < 0 Then
        ShpNxtRno = Rge.Row + J
        Exit Function
    End If
Next
End Function


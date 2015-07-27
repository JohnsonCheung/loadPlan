Attribute VB_Name = "XlsWb"
Option Explicit

Sub WbFillCellByDic(Wb As Workbook, Dic As Dictionary)
Dim K(), J%, Nm As Excel.Name
K = Dic.Keys
For J = 0 To UB(K)
    Set Nm = Wb.Names(K(J))
    Nm.RefersToRange.Value = Dic(K(J))
Next
End Sub

Function WbNew() As Workbook
Set WbNew = Application.Workbooks.Add
End Function

Function WbNewWs(Wb As Workbook, Optional AtEnd As Boolean = False) As Worksheet
If AtEnd Then
    Set WbNewWs = Wb.Sheets.Add(, Wb.Sheets(Wb.Sheets.Count))
Else
    Set WbNewWs = Wb.Sheets.Add(Wb.Sheets(1))
End If
End Function

Function WbNmAy(Wb As Workbook) As String()
Dim O$(), J&
If Wb.Names.Count = 0 Then Exit Function
ReDim O$(Wb.Names.Count - 1)
For J = 1 To Wb.Names.Count
    O(J - 1) = Wb.Names(J).Name
Next
WbNmAy = O
End Function

Function WbWsNmAy(Wb As Workbook) As String()
Dim J%, O$(), Ws As Worksheet
For Each Ws In Wb.Sheets
    Push O, Ws.Name
Next
WbWsNmAy = O
End Function


VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "LoadSheet"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public Ws1 As New Ws1
Public Ws2 As New Ws2
Public Fx$
Function NewLoadSheet(Seg$)
Fx = NewLoadSheet__GetFx(Seg)
Ws2.NewWs2 Seg     '<- must before Ws1.Init ..
Ws1.NewWs1 Seg
End Function

Sub Gen()
Application.DisplayAlerts = False
Application.ScreenUpdating = False

'---- Gen 2 worksheets
Dim Wb As Workbook
    Set Wb = WbNew
    Ws2.FillWs Wb
    Ws1.FillWs Wb

'--- Delete all other worksheet except, Nm1 Nm2
Dim Nm1$, Nm2$, Nm, Ws As Worksheet, WsNmAy$()
    Nm1 = Ws1.Name
    Nm2 = Ws2.Name
    WsNmAy = WbWsNmAy(Wb)
    For Each Nm In WsNmAy
        If Nm <> Nm1 And Nm <> Nm2 Then
            Set Ws = Wb.Sheets(Nm)
            Ws.Delete
        End If
    Next

'--- Activate Ws A1
Set Ws = Wb.Sheets(1)
Ws.Activate
Ws.Range("A1").Select
Ws.Range("A1").Activate
Ws.Range("A1").Show
Ws.Range("D2").Select

'-- Save and Close the Wb
Gen__SavWb Wb, Fx
End Sub

Private Function NewLoadSheet__GetFx(Seg$)
'Assume: Fdr is in format of Trip-2015-01-01#001
'                            1234567890123456789
'                                     1
'Return: OFx
'   where OFx = Pth & Fn
'         Pth = Hom\LoadSheet\YYYY\MM\DD\
'         Fn  = "Load-Sheet-YYYY-MM-DD#NNN.xlsx
'         Hom = is Htdocs\loadplan\, which is PthCur & "..\..\".  First .. is [pgm], Second .. is [loadplan].
'         YYYY MM DD NNN = from fdr
'         PthCur     = it is a function returning the path that this program is located
Dim Fn$, Pth$, Hom$, YYYY$, MM$, DD$, NNN$
If Len(Seg) <> 19 Then Err.Raise 1, , "Fdr Len<>19"
Hom = PthNorm(PthCur & "..\..\")
YYYY = Mid(Seg, 6, 4)
MM = Mid(Seg, 11, 2)
DD = Mid(Seg, 14, 2)
NNN = Right(Seg, 3)
Fn = "LoadSheet-" & YYYY & "-" & MM & "-" & DD & "#" & NNN & ".xlsx"
Pth = Hom & "loadSheet\" & YYYY & "\" & MM & "\" & DD & "\"
PthCrtEachSeg Pth                '<== Crt Each SEg
NewLoadSheet__GetFx = FfnNewVer(Pth & Fn)      '<== FfnNewVer
End Function

Private Sub Gen__SavWb(Wb As Workbook, Fx$)
'Return true and write to F% if any error in saving the Wb.  May be the workbook is in use.
On Error GoTo X
Wb.SaveAs Fx
Wb.Close
Exit Sub
X:
Logr.WrtMsg vbTab & "Cannot save as [" & Fx & "].  ErMsg=[" & Err.Description & "]"
End Sub



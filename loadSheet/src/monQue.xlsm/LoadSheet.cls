VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "LoadSheet"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public Wb As Workbook
Public Ws1 As New Ws1
Public Ws2 As New Ws2
Public Fx$
Function Init(Fdr$) As LoadSheet
Fx = GetFx(Fdr)
Ws2.Init Fdr     '<- must before Ws1.Init ..
Ws1.Init Fdr
Set Init = Me
End Function

Function Gen() As Boolean
Application.DisplayAlerts = False
Application.ScreenUpdating = False
Set Wb = WbNew
If Ws2.Fillin(Wb) Then Gen = True
If Ws1.Fillin(Wb) Then Gen = True
Dim Nm1$, Nm2$, Nm
    Nm1 = Ws1.Name
    Nm2 = Ws2.Name
Dim Ws As Worksheet, WsNmAy$()
WsNmAy = WbWsNmAy(Wb)
For Each Nm In WsNmAy
    If Nm <> Nm1 And Nm <> Nm2 Then
        Set Ws = Wb.Sheets(Nm)
        Ws.Delete
    End If
Next
'Set Ws = Wb.Sheets(2)
'Ws.Activate
'Ws.Range("A1").Select
'Ws.Range("A1").Activate
'Ws.Range("A1").Show

Set Ws = Wb.Sheets(1)
Ws.Activate
Ws.Range("A1").Select
Ws.Range("A1").Activate
Ws.Range("A1").Show
Ws.Range("D2").Select
Wb.SaveAs Fx
Wb.Close False
End Function

Private Function GetFx$(Fdr$)
'Fdr fmt Trip-YYYY-MM-DD#NNN
'        12345678901234567890
'                 1         2
'Fx  fmt Pth & Fn
'Pth fmt Hom\LoadSheet\YYYY\MM\DD\
'Fn  fmt "Load-Sheet-YYYY-MM-DD#NNN.xlsx
'PthCur fmt Hom\Pgm\LoadSheet\
Dim Fn$, Pth$
    Dim PthHom$
    Dim YYYY$, MM$, DD$
        PthHom = PthNorm(PthCur & "..\..\")
        YYYY = Mid(Fdr, 6, 4)
        MM = Mid(Fdr, 11, 2)
        DD = Mid(Fdr, 14, 2)
Fn = "LoadSheet-" & Mid(Fdr, 6) & ".xlsx"
Pth = PthHom & "loadSheet\" & YYYY & "\" & MM & "\" & DD & "\"

PthCrtEachSeg Pth                       '<== Crt Each SEg
GetFx = FfnNewVer(Pth & Fn)      '<== FfnNewVer
End Function

Function SavWb(Ws As Worksheet, LoadSheet$) As Boolean
'Return true and write to F% if any error in saving the Ws
'On Error GoTo X
Exit Function
X:
WrtLog vbTab & "Cannot save as [" & LoadSheet & "].  ErMsg=[" & Err.Description & "]"
SavWb = True
End Function



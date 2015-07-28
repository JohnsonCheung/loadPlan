Attribute VB_Name = "LoadSheet__Tst"
Option Explicit
Const Inspect = True
Sub TstAll_LoadSheet()
Fx__Tst
Gen__Tst
End Sub

Private Sub Fx__Tst()
Dim Exp$
    Exp = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\LoadSheet-2015-01-01#001.xlsx"
    FfnDlt Exp

Dim Act$
    Act = NewLoadSheet(C_Seg1).Fx

Debug.Assert Act = Exp
Pass "Fx__Tst"
End Sub

Private Sub Gen__Tst()
Const OFdr = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\"
    PthDlt_File OFdr
    Debug.Assert Sz(PthAyFn(OFdr)) = 0

CpyQue
Debug.Assert Sz(PthAyFdr(LSPth.Que)) = 2

Dim QueSegAy$()
    QueSegAy = LSApp.QueSegAy

Dim J%
For J = 0 To Sz(QueSegAy) - 1
    NewLoadSheet(QueSegAy(J)).Gen   '<=======
Next

Debug.Assert Sz(PthAyFdr(LSPth.Que)) = 2     '<== LoadSheet.Gen does not mov/dlt of folder

Dim Ay$()
    Ay = PthAyFn(OFdr)
    Debug.Assert Sz(Ay) = 2
    Debug.Assert Ay(0) = "LoadSheet-2015-01-01#001.xlsx"
    Debug.Assert Ay(1) = "LoadSheet-2015-01-01#002.xlsx"

Dim Ws As Worksheet

Dim Wb1 As Workbook
Dim Wb2 As Workbook
    Set Wb1 = Application.Workbooks.Open(OFdr & Ay(0))
    Set Wb2 = Application.Workbooks.Open(OFdr & Ay(1))
    Debug.Assert Wb1.Sheets.Count = 2
    Debug.Assert Wb2.Sheets.Count = 2
    Set Ws = Wb1.Sheets(1): Debug.Assert Ws.Name = "¸ü³f¯È"
    Set Ws = Wb1.Sheets(2): Debug.Assert Ws.Name = "ªþ­¶"
    Set Ws = Wb2.Sheets(1): Debug.Assert Ws.Name = "¸ü³f¯È"
    Set Ws = Wb2.Sheets(2): Debug.Assert Ws.Name = "ªþ­¶"
    If Inspect Then
        Application.Visible = True
        Stop
    End If
    Wb2.Close False
    Wb1.Close False
Pass "Gen__Tst"
End Sub


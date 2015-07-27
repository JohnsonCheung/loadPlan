Attribute VB_Name = "LoadSheet__Tst"
Option Explicit
Sub TstAll_LoadSheet()
Fx__Tst
Gen__Tst
End Sub


Private Sub Fx__Tst()
Dim Act$
Dim Exp$
    Act = NewLoadSheet(C_Seg1).Fx
    Exp = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\LoadSheet-2015-01-01#001.xlsx"
Debug.Assert Act = Exp
Pass "Fx__Tst"
End Sub

Private Sub Gen__Tst()
Const OFdr = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\"
    PthDlt_File OFdr
    Debug.Assert Sz(PthAyFn(OFdr)) = 0

CpyQue
Debug.Assert Sz(PthAyFdr(LSPth.Que)) = 2

Dim M As LoadSheet
Dim QueSegAy$()
    LSApp.QueSegAy

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

Dim Wb As Workbook, Ws As Worksheet
    Set Wb = Application.Workbooks.Open(OFdr & Ay(0))
    Debug.Assert Wb.Sheets.Count = 2
    Set Ws = Wb.Sheets(1): Debug.Assert Ws.Name = "¸ü³f¯È"
    Set Ws = Wb.Sheets(2): Debug.Assert Ws.Name = "ªþ­¶"
    Wb.Close False

    Set Wb = Application.Workbooks.Open(OFdr & Ay(1))
    Debug.Assert Wb.Sheets.Count = 2
    Set Ws = Wb.Sheets(1): Debug.Assert Ws.Name = "¸ü³f¯È"
    Set Ws = Wb.Sheets(2): Debug.Assert Ws.Name = "ªþ­¶"
    Wb.Close False
Pass "Gen__Tst"
End Sub


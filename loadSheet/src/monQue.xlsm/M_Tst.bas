Attribute VB_Name = "M_Tst"
Option Explicit
Private Act_Ay$()
Private Act$
Private Exp$
Private Act_Bool As Boolean
Private Exp_Bool As Boolean
Dim FillLoadSheet As New FillLoadSheet
Dim FillRmk As New FillRmk
Dim FillDrop As New FillDrop
Dim FillDrops As New FillDrops
Dim FillHdr As New FillHdr
Dim FillWs1 As New FillWs1
Dim FillWs2 As New FillWs2
Dim FillBarCd As New FillBarCd
Dim Fdr As New Fdr
Private F%
Const C_Fdr = "Trip-2015-01-01#001"
Const C_FdrQ2 = "Trip-2015-01-01#002"
Const C_FdrQ1 = "Trip-2015-01-01#001"
Private Sub TstAll()
Setup
If True Then
Else
    Tst_Log_FtLog
    Tst_Log_BrwLog
    Tst_Log_WrtLog
    Tst_Log_WrtLogAy
End If
If True Then
    Tst_FillWs2_FillIn
Else
    Tst_FillBarCd_Init
    Tst_FillBarCd_PutBarCd
    Tst_FillWs1_Init
    Tst_FillWs1_FillIn
    Tst_FillWs2_Init
    Tst_FillWs2_rPutOneAtt
    Tst_FillWs2_FillIn
    Tst_FillHdr_Init
    Tst_FillHdr_Fill
    Tst_FillLoadSheet_Fx
    Tst_FillLoadSheet_Gen
    Tst_FillRmk_Fill
    Tst_Fdr_Init
    Tst_Fdr_Gen
    Tst_Fdr_MovToEr
    Tst_FfnContent
    Tst_PthQueErr
    Tst_Que_SchNxtTick_TmrChkQue
End If
End Sub

Private Sub Tst_FillBarCd_Init()
FillBarCd.Init C_Fdr
Debug.Assert FillBarCd.BarCdFfn = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\BarCd.png"
End Sub

Private Sub Tst_FillBarCd_PubBarCd()
Dim Wb As Workbook, Ws As Worksheet
Set Wb = WbNew
WsLoadSheet.Copy Wb.Sheets(1)
Set Ws = Wb.Sheets(1)
FillBarCd.Init C_Fdr
FillBarCd.PutBarCd Ws
Stop ' The BarCd should be put.
Wb.Close False
End Sub

Private Sub Tst_Log_FtLog()
Dim D$
D = Format(Date, "YYYY-MM-DD")
Debug.Assert FtLog = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Log\2015\06\LoadSheetProcess-" & D & ".log"
End Sub

Private Sub Tst_Log_BrwLog()
BrwLog
End Sub

Private Sub Tst_Log_WrtLog()
Dim A$, Ay$()
A = "AAA " & Now
WrtLog A
Ay = FtAy(FtLog)
Debug.Assert Ay(Sz(Ay) - 2) = A ' note:-2 instead of -1, becuase the last line is empty.
End Sub

Private Sub Tst_Log_WrtLogAy()
Dim A$(2)
A(0) = 1 & " " & Now
A(1) = 2 & " " & Now
A(2) = 3 & " " & Now
WrtLogAy A
Dim Ay$()
Ay = FtAy(FtLog)
Debug.Assert Ay(Sz(Ay) - 4) = A(0)
Debug.Assert Ay(Sz(Ay) - 3) = A(1)
Debug.Assert Ay(Sz(Ay) - 2) = A(2)
End Sub

Sub Setup()
CpyQue
End Sub

Private Sub Tst_Fdr_Init()
Fdr.Init C_FdrQ1
Debug.Assert Fdr.PthInp = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\" & C_FdrQ1 & "\"
Fdr.Init C_FdrQ2
Debug.Assert Fdr.PthInp = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\" & C_FdrQ2 & "\"
Pass "Tst_Fdr_Init"
End Sub

Private Sub Tst_Fdr_Gen()
Que.ClrQue
CpyQue

Dim N%
    N = Sz(PthAyFdr(PthQueErr))

Dim Ay$()
    Ay = PthAyFdr(PthQue)
    Debug.Assert Sz(Ay) = 2
    Debug.Assert Ay(0) = C_FdrQ1
    Debug.Assert Ay(1) = C_FdrQ2

Act_Bool = Fdr.Init(C_FdrQ1).Gen
    Debug.Assert Act_Bool = False
    Ay = PthAyFdr(PthQue)
    Debug.Assert Sz(Ay) = 1
    Debug.Assert Ay(0) = C_FdrQ2

Debug.Assert N = Sz(PthAyFdr(PthQueErr))

Act_Bool = Fdr.Init(C_FdrQ2).Gen
    Debug.Assert Act_Bool = False
    Ay = PthAyFdr(PthQue)
    Debug.Assert Sz(Ay) = 0

Debug.Assert N = Sz(PthAyFdr(PthQueErr))

CpyQue
Pass "Tst_Fdr_Gen"
End Sub

Private Sub Tst_FillWs1_Init()
Dim Wb As Workbook
Application.ScreenUpdating = False
Set Wb = WbNew
FillWs1.Init C_Fdr
Debug.Assert FillWs1.A_Ws.Name = "¸ü³f¯È"
Wb.Close False
Pass "Tst_FillWs1_init"
End Sub

Private Sub Tst_FillWs1_FillIn()
Dim Wb As Workbook
Application.ScreenUpdating = False
Set Wb = WbNew
FillWs1.Init C_Fdr
Act_Bool = FillWs1.Fillin(Wb)
Stop
Wb.Close False
Pass "Tst_FillWs1_FillIn"
End Sub

Private Sub Tst_FillHdr_Init()
'Setup
FillHdr.Init C_Fdr
Debug.Assert FillHdr.Driver = "Driver1"
Debug.Assert FillHdr.DriverTy = "DriverTy"
Debug.Assert FillHdr.Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\hdr.txt"
Debug.Assert FillHdr.Member = "Member1"
Debug.Assert FillHdr.NoEr = True
Debug.Assert FillHdr.Leader = "Leader1"
Debug.Assert FillHdr.TripChiNm = "2015-02-01-Trip-001"

Set FillHdr = New FillHdr
FillHdr.Init "abc"
Debug.Assert FillHdr.Driver = ""
Debug.Assert FillHdr.DriverTy = ""
Debug.Assert FillHdr.Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\abc\hdr.txt"
Debug.Assert FillHdr.Member = ""
Debug.Assert FillHdr.NoEr = False
Debug.Assert FillHdr.Leader = ""
Debug.Assert FillHdr.TripChiNm = ""
BrwLog
Pass "Tst_FillHdr_Init"
End Sub

Private Sub Tst_FillHdr_Fill()
Dim Ws As Worksheet, Wb As Workbook
Set Wb = WbNew
FillWs1.Init C_Fdr
FillWs1.FillHdr.Fillin FillWs1.A_Ws
Set Wb = FillWs1.A_Ws.Parent
Wb.Close False
Pass "Tst_FillHdr_Fill"
End Sub

Private Sub Tst_FillRmk_Init()
Shell PthQue & "CpyQue.bat"
FillRmk.Init C_Fdr

Debug.Assert FillRmk.NLin = 6 + 3

Dim A$()

A = FillRmk.RmkAy_OfAdr
    Debug.Assert A(0) = "Adr Rmk 1 Line1\nLine2\nLine3"
    Debug.Assert A(1) = "Adr Rmk 2 Line1\nLine2\nLine3"
    Debug.Assert A(2) = "Adr Rmk 3 Line1\nLine2\nLine3"


A = FillRmk.RmkAy_OfOrd
    Debug.Assert A(0) = "Ord Rmk 1 Line1\nLine2\nLine3"
    Debug.Assert A(1) = "Ord Rmk 2 Line1\nLine2\nLine3"
    Debug.Assert A(2) = "Ord Rmk 3 Line1\nLine2\nLine3"
Pass "Tst_FillRmk_Init    "
End Sub
Sub AAAA()
Tst_FillRmk_Fill
End Sub
Private Sub Tst_FillRmk_Fill()
Application.ScreenUpdating = False

Dim Ws As Worksheet
Dim Wb As Workbook, App As Application
    Set Ws = WsNew
    Set Wb = Ws.Parent
    
FillRmk.Init C_Fdr

Act_Bool = FillRmk.FillRmk(Ws, 12) '<====
Debug.Assert Act_Bool = False


Debug.Assert Ws.Range("A12").Value = "*¬_¥´°e³f«ü¥Ü"
Debug.Assert Ws.Range("A13").Value = "*1"
Debug.Assert Ws.Range("A14").Value = "*2"
Debug.Assert Ws.Range("A15").Value = "*3"

Debug.Assert Ws.Range("B13").Value = Replace("Ord Rmk 1 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B14").Value = Replace("Ord Rmk 2 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B15").Value = Replace("Ord Rmk 3 Line1\nLine2\nLine3", "\n", vbCrLf)

Debug.Assert Ws.Range("A17").Value = "@¦a§}°e³f«ü¥Ü"
Debug.Assert Ws.Range("A18").Value = "@1"
Debug.Assert Ws.Range("A19").Value = "@2"
Debug.Assert Ws.Range("A20").Value = "@3"

Debug.Assert Ws.Range("B18").Value = Replace("Adr Rmk 1 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B19").Value = Replace("Adr Rmk 2 Line1\nLine2\nLine3", "\n", vbCrLf)
Debug.Assert Ws.Range("B20").Value = Replace("Adr Rmk 3 Line1\nLine2\nLine3", "\n", vbCrLf)
'Stop
Wb.Close False
Application.ScreenUpdating = True
Pass "Tst_FillRmk"
End Sub

Private Sub Tst_FillBarCd_PutBarCd()
Dim Ws As Worksheet, Wb As Workbook
Set Ws = WsNew
Set Wb = Ws.Parent
WsLoadSheet.Copy Ws
Set Ws = Wb.Sheets(1)

FillBarCd.Init C_Fdr
FillBarCd.PutBarCd Ws
Ws.Application.Visible = True
BrwLog
Pass "Tst_PutBarCd"
End Sub

Private Sub Tst_FillWs2_rPutOneAtt()
Dim Ws As Worksheet, Wb As Workbook
Dim AttFn$, CusCd$, ContentRmk$
Dim Rno%

FillWs2.Init C_Fdr

Rno = 2
AttFn = "Trip-2015-01-01#001 Att-02 (Ord-2015-01-01#1234 Content-02).png"

Set Wb = WbNew
WsAtt.Copy Wb.Sheets(1)
Set Ws = Wb.Sheets(1)

Act_Bool = FillWs2.rPutOneAtt(Ws, Rno, CusCd, ContentRmk, AttFn)
Debug.Assert Rno = 7
Pass "Tst_PutOneAtt"
End Sub

Private Sub Tst_FillWs2_Init()
Que.ClrQue
CpyQue1
FillWs2.Init C_Fdr
Dim Ay$()
Ay = FillWs2.AttFnAy
Debug.Assert Sz(Ay) = 2
Debug.Assert Ay(0) = "Trip-2015-01-01#001 att-01 (ord-2015-01-01#1234 content-01).png"
Debug.Assert Ay(1) = "Trip-2015-01-01#001 att-02 (ord-2015-01-01#1234 content-02).png"

Debug.Assert FillWs2.Name = "ªþ­¶"
Que.ClrQue
CpyQue
Pass "Tst_FillWs2_Init"
End Sub

Private Sub Tst_FillWs2_FillIn()
Dim Wb As Workbook
Application.ScreenUpdating = False
Set Wb = WbNew
FillWs2.Init C_Fdr
Act_Bool = FillWs2.Fillin(Wb)
Debug.Assert Act_Bool = False ' false for no error
Stop
Wb.Close False
Pass "Tst_FillWs2_FillIn"
End Sub

Private Sub Tst_Que_SchNxtTick_TmrChkQue()
Que.ClrQue
CpyQue
Que.SchNxtTick_TmrChkQue
Que.BrwFdr
BrwLog
Pass "Tst_Que_Timer_Check"
End Sub

Private Sub Tst_FfnContent()
Debug.Assert FfnContent("150101", 1, 1) = "C:\xampp\htdocs\loadPlan\ordContent\2015\01\01\0001\Ord-20150101-0001-01.png"
End Sub

Private Sub Tst_Fdr_MovToEr()
CpyQue
Dim Ay$()
    Ay = Que.FdrAy
    Debug.Assert Sz(Ay) = 2
    Debug.Assert Ay(0) = "Trip-2015-01-01#001"
    Debug.Assert Ay(1) = "Trip-2015-01-01#002"
    
PthDlt_Fdr PthQueErr

Dim FdrAy$()
    FdrAy = PthAyFdr(PthQueErr)
    
Debug.Assert Sz(FdrAy) = 0

Dim J%, A$
For J = 0 To UB(Ay)
    Fdr.Init Ay(J)
    A = Fdr.MovToEr
    Debug.Assert IsPth(A)
Next
CpyQue
Pass "Tst_MovFdrToEr"
End Sub


Private Sub Tst_PthQueErr()
Debug.Assert PthQueErr = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\QueErr\"
End Sub
Sub CpyQue()
CpyQue1
CpyQue2
End Sub
Sub CpyQue1()
If False Then
    Shell PthQue & "CpyQue1.bat", vbHide
Else
    Dim ToPth$, FmPth$
    ToPth = PthQue & C_FdrQ1
    FmPth = PthQueTest & C_FdrQ1
    FSO.CopyFolder FmPth, ToPth
End If
End Sub
Sub CpyQue2()
If False Then
    Shell PthQue & "CpyQue2.bat", vbHide
Else
    Dim ToPth$, FmPth$
    ToPth = PthQue & C_FdrQ2
    FmPth = PthQueTest & C_FdrQ2
    FSO.CopyFolder FmPth, ToPth
End If
End Sub

Private Sub Tst_FillLoadSheet_Fx()
FillLoadSheet.Init C_Fdr
Act = FillLoadSheet.Fx
Exp = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\LoadSheet-2015-01-01#001.xlsx"
Debug.Assert Act = Exp
Pass "Tst_FillLoadSheet"
End Sub

Private Sub Tst_FillLoadSheet_Gen()
Const OFdr = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\"
    PthDlt_File OFdr
    Debug.Assert Sz(PthAyFn(OFdr)) = 0

CpyQue
Debug.Assert Sz(PthAyFdr(PthQue)) = 2

Dim Ay$()
    Ay = Que.FdrAy()
Dim J%, NOk%, NEr%
For J = 0 To Sz(Ay) - 1
    FillLoadSheet.Init Ay(J)
    If FillLoadSheet.Gen Then '<=======
        NEr = NEr + 1
    Else
        NOk = NOk + 1
    End If
Next

Debug.Assert NEr = 0
Debug.Assert NOk = 2
Debug.Assert Sz(PthAyFdr(PthQue)) = 2     '<== LoadSheet.Gen does not mov/dlt of folder

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

Pass "Tst_FillLoadSheet_Gen"
End Sub

Property Get PthQueTest$()
Static O$
If O = "" Then O = PthCur & "QueTest\"
PthQueTest = O
End Property




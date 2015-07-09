Attribute VB_Name = "M_Tst"
Option Explicit
Private Act_Ay$()
Private Act$
Private Exp$
Private Act_Bool As Boolean
Private Exp_Bool As Boolean
Dim LoadSheet As New LoadSheet
Dim Rmk As New Rmk
Dim Drop As New Drop
Dim Drops As New Drops
Dim Fdr As New Fdr
Dim Hdr As New Hdr
Dim Ws1 As New Ws1
Dim Ws2 As New Ws2
Dim BarCd As New BarCd
Private F%
Const C_Fdr = "Trip-2015-01-01#001"
Const C_FdrQ2 = "Trip-2015-01-01#002"
Const C_FdrQ1 = "Trip-2015-01-01#001"
Sub TstAll()
Setup
If True Then
Else
    Tst_Log_FtLog
    Tst_Log_BrwLog
    Tst_Log_WrtLog
    Tst_Log_WrtLogAy
End If
If True Then
    Tst_Ws2_FillIn
Else
    Tst_BarCd_Init
    Tst_BarCd_PutBarCd
    Tst_Ws1_Init
    Tst_Ws1_FillIn
    Tst_Ws2_Init
    Tst_Hdr_Init
    Tst_Hdr_Fill
    Tst_Fdr_Init
    Tst_Fdr_Gen
    Tst_Fdr_MovToEr
    Tst_FfnContent
    Tst_LoadSheet_Init
    Tst_LoadSheet_Fx
    Tst_LoadSheet_Gen
    Tst_PthQueErr
    Tst_Que_SchNxtTick_TmrChkQue
    Tst_Rmk_Fill
    Tst_Ws2_PutOneAtt
End If
End Sub

Sub Tst_BarCd_Init()
BarCd.Init C_Fdr
Debug.Assert BarCd.BarCdFfn = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\BarCd.png"
End Sub

Sub Tst_BarCd_PubBarCd()
Dim Wb As Workbook, Ws As Worksheet
Set Wb = WbNew
WsLoadSheet.Copy Wb.Sheets(1)
Set Ws = Wb.Sheets(1)
BarCd.Init(C_Fdr).PutBarCd Ws
Stop ' The BarCd should be put.
Wb.Close False
End Sub

Sub Tst_Log_FtLog()
Dim D$
D = Format(Date, "YYYY-MM-DD")
Debug.Assert FtLog = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Log\2015\06\LoadSheetProcess-" & D & ".log"
End Sub

Sub Tst_Log_BrwLog()
BrwLog
End Sub

Sub Tst_Log_WrtLog()
Dim A$, Ay$()
A = "AAA " & Now
WrtLog A
Ay = FtAy(FtLog)
Debug.Assert Ay(Sz(Ay) - 2) = A ' note:-2 instead of -1, becuase the last line is empty.
End Sub

Sub Tst_Log_WrtLogAy()
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

Sub Tst_Fdr_Init()
Fdr.Init C_FdrQ1
Debug.Assert Fdr.PthInp = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\" & C_FdrQ1 & "\"
Fdr.Init C_FdrQ2
Debug.Assert Fdr.PthInp = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\" & C_FdrQ2 & "\"
Pass "Tst_Fdr_Init"
End Sub

Sub Tst_Fdr_Gen()
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

Sub Tst_Ws1_Init()
Dim Wb As Workbook
Application.ScreenUpdating = False
Set Wb = WbNew
Ws1.Init C_Fdr
Debug.Assert Ws1.A_Ws.Name = "¸ü³f¯È"
Wb.Close False
Pass "Tst_Ws1_init"
End Sub

Sub Tst_Ws1_FillIn()
Dim Wb As Workbook
Application.ScreenUpdating = False
Set Wb = WbNew
Ws1.Init C_Fdr
Act_Bool = Ws1.Fillin(Wb)
Stop
Wb.Close False
Pass "Tst_Ws1_FillIn"
End Sub

Sub Tst_Hdr_Init()
'Setup
Hdr.Init C_Fdr
Debug.Assert Hdr.Driver = "Driver1"
Debug.Assert Hdr.DriverTy = "DriverTy"
Debug.Assert Hdr.Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\Trip-2015-01-01#001\hdr.txt"
Debug.Assert Hdr.Member = "Member1"
Debug.Assert Hdr.NoEr = True
Debug.Assert Hdr.Leader = "Leader1"
Debug.Assert Hdr.TripChiNm = "2015-02-01-Trip-001"

Set Hdr = New Hdr
Hdr.Init "abc"
Debug.Assert Hdr.Driver = ""
Debug.Assert Hdr.DriverTy = ""
Debug.Assert Hdr.Ft = "C:\xampp\htdocs\loadPlan\pgm\loadSheet\Que\abc\hdr.txt"
Debug.Assert Hdr.Member = ""
Debug.Assert Hdr.NoEr = False
Debug.Assert Hdr.Leader = ""
Debug.Assert Hdr.TripChiNm = ""
BrwLog
Pass "Tst_Hdr_Init"
End Sub

Sub Tst_Hdr_Fill()
Dim Ws As Worksheet, Wb As Workbook
Set Wb = WbNew
Ws1.Init C_Fdr
Ws1.Hdr.Fillin Ws1.A_Ws
Set Wb = Ws1.A_Ws.Parent
Wb.Close False
Pass "Tst_Hdr_Fill"
End Sub

Sub Tst_Rmk_Init()
Shell PthCur & "CpyQue.bat"
Rmk.Init C_Fdr
Const Exp_NLin% = 4
Debug.Assert Rmk.NLin = Exp_NLin
Act_Ay = Rmk.RmkAy
Debug.Assert Sz(Act_Ay) = Exp_NLin
Debug.Assert Act_Ay(0) = ""
    Act_Ay(0) = "RmkKey-1,RmkKey-1 Line#1 of 3\nRmkKey-1 Line#2 of 3.....\nRmkKey-1 Line#3 of 3....."
    Act_Ay(1) = "RmkKey-2,RmkKey-1 Line#1 of 1"
    Act_Ay(2) = "RmkKey-3,RmkKey-1 Line#1 of 2\nRmkKey-1 Line#2 of 2"
Pass "Tst_Rmk_Init    "
End Sub

Sub Tst_Rmk_Fill()
Dim Ws As Worksheet
Dim Wb As Workbook, App As Application
    Set Ws = WsNew
    Set Wb = Ws.Parent
    Set App = Wb.Application
    App.ScreenUpdating = False
    
Rmk.Init C_Fdr

Act_Bool = Rmk.FillRmk(Ws, 12)
Debug.Assert Act_Bool = False

'Rmk.Txt
'1,Rmk#1 Line#1 sdlkf jslkdf slkdjf sdf jsdf\nLIne#2 sldkfj sldkfj sdlfjk dfs\nLine#3 sldkfj sldkfj dklfj sdklfdf
'1(-1),Rmk#2 lskdjf lskdjf lskdjf skldfj slkdfj
'1.1,Rmk#3 sdlfj sdlkfj dsklfj sdlkj dfj sdlf
'1.2,Rmk#3 sdlfj sdlkfj dsklfj sdlkj dfj sdlf

Debug.Assert Ws.Range("A13").Value = "#1"
Debug.Assert Ws.Range("A14").Value = "#2"
Debug.Assert Ws.Range("A15").Value = "#3"
Debug.Assert Ws.Range("A16").Value = "#4"

Debug.Assert Ws.Range("B13").Value = "1"
Debug.Assert Ws.Range("B14").Value = "1(-1)"
Debug.Assert Ws.Range("B15").Value = "1.1"
Debug.Assert Ws.Range("B16").Value = "1.2"

Debug.Assert Ws.Range("C13").Value = Replace("Rmk#1 Line#1 sdlkf jslkdf slkdjf sdf jsdf\nLIne#2 sldkfj sldkfj sdlfjk dfs\nLine#3 sldkfj sldkfj dklfj sdklfdf", "\n", vbCrLf)
Debug.Assert Ws.Range("C14").Value = "Rmk#2 lskdjf lskdjf lskdjf skldfj slkdfj"
Debug.Assert Ws.Range("C15").Value = Replace("Rmk#3 sdlfj sdlkfj dsklfj sdlkj dfj sdlf", "\n", vbCrLf)
Debug.Assert Ws.Range("C16").Value = Replace("Rmk#3 sdlfj sdlkfj dsklfj sdlkj dfj sdlf", "\n", vbCrLf)
Stop
Wb.Close False
App.ScreenUpdating = True
Pass "Tst_FillRmk"
End Sub

Sub Tst_BarCd_PutBarCd()
Dim Ws As Worksheet, Wb As Workbook
Set Ws = WsNew
Set Wb = Ws.Parent
WsLoadSheet.Copy Ws
Set Ws = Wb.Sheets(1)

BarCd.Init C_Fdr
BarCd.PutBarCd Ws
Ws.Application.Visible = True
BrwLog
Pass "Tst_PutBarCd"
End Sub

Sub Tst_Ws2_PutOneAtt()
Dim Ws As Worksheet, Wb As Workbook
Dim AttFn$
Dim Rno%

Ws2.Init C_Fdr

Rno = 2
AttFn = "Trip-2015-01-01#001 Att-02 (Ord-2015-01-01#1234 Content-02).png"

Set Wb = WbNew
WsAtt.Copy Wb.Sheets(1)
Set Ws = Wb.Sheets(1)

Act_Bool = Ws2.PutOneAtt(Ws, Rno, AttFn)
Debug.Assert Rno = 7
Pass "Tst_PutOneAtt"
End Sub

Sub Tst_Ws2_Init()
Que.ClrQue
CpyQue1
Ws2.Init C_Fdr
Dim Ay$()
Ay = Ws2.AttFnAy
Debug.Assert Sz(Ay) = 2
Debug.Assert Ay(0) = "Trip-2015-01-01#001 Att-01 (Ord-2015-01-01#1234 Content-01).PNG"
Debug.Assert Ay(1) = "Trip-2015-01-01#001 Att-02 (Ord-2015-01-01#1234 Content-02).PNG"

Debug.Assert Ws2.Name = "ªþ­¶"
Que.ClrQue
CpyQue
Pass "Tst_Ws2_Init"
End Sub

Sub Tst_Ws2_FillIn()
Dim Wb As Workbook
Application.ScreenUpdating = False
Set Wb = WbNew
Act_Bool = Ws2.Init(C_Fdr).Fillin(Wb)
Debug.Assert Act_Bool = False ' false for no error
Stop
Wb.Close False
Pass "Tst_Ws2_FillIn"
End Sub

Sub Tst_Que_SchNxtTick_TmrChkQue()
Que.ClrQue
CpyQue
Que.SchNxtTick_TmrChkQue
Que.BrwFdr
BrwLog
Pass "Tst_Que_Timer_Check"
End Sub

Sub Tst_FfnContent()
Debug.Assert FfnContent("150101", 1, 1) = "C:\xampp\htdocs\loadPlan\ordContent\2015\01\01\0001\Ord-20150101-0001-01.png"
End Sub

Sub Tst_Fdr_MovToEr()
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

Sub Tst_LoadSheet_Gen()
Const OFdr = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\"
    PthDlt_File OFdr
    Debug.Assert Sz(PthAyFn(OFdr)) = 0

CpyQue
Debug.Assert Sz(PthAyFdr(PthQue)) = 2

Dim Ay$()
    Ay = Que.FdrAy()
Dim J%, NOk%, NEr%
For J = 0 To Sz(Ay) - 1
    If LoadSheet.Init(Ay(J)).Gen Then '<=======
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

Pass "Tst_LoadSheet_Gen"
End Sub

Sub Tst_PthQueErr()
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

Sub Tst_LoadSheet_Fx()
LoadSheet.Init C_Fdr
Act = LoadSheet.Fx
Exp = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\LoadSheet-2015-01-01#12.xlsx"
Debug.Assert Act = Exp
Pass "Tst_LoadSheet"
End Sub

Sub Tst_LoadSheet_Init()
Dim Ws As Worksheet
LoadSheet.Init C_Fdr
Act = LoadSheet.Fx
Exp = "C:\xampp\htdocs\loadPlan\loadSheet\2015\01\01\LoadSheet-2015-01-01#001"
Debug.Assert IsPfx(Exp, Act)
Debug.Assert IsSfx(".xlsx", Act)
Debug.Assert LoadSheet.Wb.Sheets.Count = 2
Set Ws = LoadSheet.Wb.Sheets(1): Debug.Assert Ws.Name = "¸ü³f¯È"
Set Ws = LoadSheet.Wb.Sheets(2): Debug.Assert Ws.Name = "ªþ­¶"
Pass "Tst_LoadSheet_Init"
End Sub

Property Get PthQueTest$()
Static O$
If O = "" Then O = PthCur & "QueTest\"
PthQueTest = O
End Property




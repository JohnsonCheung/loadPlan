VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Que"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Private Ticks&
Private IsStopSchedule  As Boolean
Sub StartSch()
Application.DisplayAlerts = False
IsStopSchedule = False
Ticks = 0
Start_ClrA8andBelow
Start_UpdStartTime
SchNxtTick
Application.DisplayAlerts = True
End Sub
Sub StopSch()
IsStopSchedule = False
End Sub

Property Get NItm%()
NItm = Sz(FdrAy)
End Property

Sub SchNxtTick()
If IsStopSchedule Then Exit Sub
Application.OnTime Now() + TimeValue("0:0:3"), "SchNxtTick_TmrChkQue", Now() + TimeValue("0:0:10")
End Sub
Property Get IsSomFdr() As Boolean
IsSomFdr = Sz(Me.FdrAy) > 0
End Property
Sub SchNxtTick_TmrChkQue()
'On Error GoTo Er
SchNxtTick_TmrChkQue_UpdTick

SchNxtTick_TmrChkQue_LogBeg Me.FdrAy

Dim NEr%, NOk%
While Me.IsSomFdr
    Dim F As Fdr
        Set F = Me.FdrObj

    SchNxtTick_TmrChkQue_UpdFdr F.A_Fdr
    If F.Gen Then '<==== Generate the loading Sheet Fx by FdrObj
        NEr = NEr + 1
    Else
        NOk = NOk + 1
    End If
    WrtLog vbTab & NEr & " errors and " & NOk & " ok. " & Format(Now(), "YYYY-MM-DD HH:MM:SS")
Wend
SchNxtTick
Exit Sub
Er:
    Dim M$()
    Push M, "*********"
    Push M, "** ERR **: " & Err.Description & " Src=[" & Err.Source & "]"
    Push M, "*********"
    WrtLogAy M
End Sub

Private Sub SchNxtTick_TmrChkQue_LogBeg(FdrAy$())
Dim N%
    N = Sz(FdrAy)
    If Sz(FdrAy) = 0 Then Exit Sub
Dim M$()
    Push M, Format(Now(), "YYYY-MM-DD HH:MM:SS")
    Push M, vbTab & N & " folder is in [" & PthQue & "]"

Dim J%
For J = 0 To UB(FdrAy)
    Push M, vbTab & " : " & FdrAy(J)
Next
End Sub

Private Sub SchNxtTick_TmrChkQue_UpdFdr(Fdr$)
Const Rno = 8
WsMonitor.Range("A" & Rno).EntireRow.Insert
WsMonitor.Range("A" & Rno).Value = NowStr
WsMonitor.Range("B" & Rno).Value = Fdr
End Sub

Private Sub SchNxtTick_TmrChkQue_UpdTick()
ThisWorkbook.Names("Ticks").RefersToRange.Value = NowStr & " (" & Ticks & ")"
Ticks = Ticks + 1
End Sub

Sub Start_UpdStartTime()
ThisWorkbook.Names("Started").RefersToRange.Value = NowStr
End Sub

Property Get FdrObj() As Fdr
'Return a new FdrObj by using the first element in Me.FdrAy if any, else return nothing
Dim A$()
A = Me.FdrAy
If Sz(A) > 0 Then
    Dim O As New Fdr
    O.Init A(0)
    Set FdrObj = O
End If
End Property

Property Get NewFdrAy() As String()
Static LastFdrAy$()
Dim O$()
O = FdrAy
If AyIsEq(O, LastFdrAy) Then Exit Property
LastFdrAy = O
NewFdrAy = O
End Property

Property Get FdrAy() As String()
FdrAy = PthAyFdr(PthQue)
End Property

Sub BrwFdr()
PthOpn PthQue
End Sub

Sub ClrQue()
Dim FdrAy$()
FdrAy = PthAyFdr(PthQue)
Dim J%
For J = 0 To UB(FdrAy)
    PthDlt PthQue & FdrAy(J) & "\"
Next
End Sub

Sub Start_ClrA8andBelow()
Dim Rge As Range
Dim L As Range
Set L = WsMonitor.Cells.SpecialCells(xlCellTypeLastCell)
If L.Row < 8 Then Exit Sub
Set Rge = WsMonitor.Range("$A$8:" & L.Address)
Rge.Clear
End Sub

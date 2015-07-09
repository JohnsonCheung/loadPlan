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

Sub SchNxtTick_TmrChkQue()
'On Error GoTo Er
SchNxtTick_TmrChkQue_UpdTick
Dim Ay$()
Ay = FdrAy
If Sz(Ay) > 0 Then
    Dim NEr%, NOk%, Fdr As Fdr, J%
    SchNxtTick_TmrChkQue_LogBeg Ay
    For J = 0 To UB(Ay)
        SchNxtTick_TmrChkQue_UpdFdr Ay(J)
        Set FdrObj = New Fdr
        If FdrObj.Init(Ay(J)).Gen Then '<==== Generate the loading Sheet Fx by FdrObj
            NEr = NEr + 1
        Else
            NOk = NOk + 1
        End If
    Next
    WrtLog vbTab & NEr & " errors and " & NOk & " ok. " & Format(Now(), "YYYY-MM-DD HH:MM:SS")
End If
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
Dim M$(), N%, J%
Push M, Format(Now(), "YYYY-MM-DD HH:MM:SS")
N = Sz(FdrAy)
Push M, vbTab & N & " folder is in [" & PthQue & "]"
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

Function LoadSheet() As LoadSheet
Dim A$()
A = FdrAy
If Sz(A) = 0 Then Exit Function
Dim O As LoadSheet
Set O = New LoadSheet
Set LoadSheet = O.Init(FdrObj)
End Function

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

VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Hdr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public TripChiNm As String
Public DriverTy As String
Public Driver As String
Public Leader As String
Public Member As String
Public NoEr As Boolean
Public Ft$

Function Init(Fdr$) As Hdr
Dim S As TextStream
On Error GoTo Er
Ft = PthQue & Fdr & "\hdr.txt"
Set S = FSO.OpenTextFile(Ft)
TripChiNm = S.ReadLine ' 1
DriverTy = S.ReadLine      ' 2
Driver = S.ReadLine         ' 3
Leader = S.ReadLine         ' 4
Member = S.ReadLine        ' 5
NoEr = True
S.Close
Exit Function
Er:
    Dim ErMsg$
    ErMsg = Err.Description
    Dim M$()
    Push M, vbTab & "Reading Hdr file Error:"
    Push M, vbTab & "   FtHdr=[" & Ft & "]"
    Push M, vbTab & "   ErMsg=[" & ErMsg & "]"
    WrtLogAy M
    ClsTextStream S
End Function

Function Fillin(Ws As Worksheet) As Boolean
Const H_TripChiNm = "TripChiNm"
Const H_DriverTy = "DriverTy"
Const H_Driver = "Driver"
Const H_Leader = "Leader"
Const H_Member = "Member"
If Not NoEr Then Fillin = True: Exit Function
Dim Ay$()
Push Ay, H_TripChiNm
Push Ay, H_DriverTy
Push Ay, H_Driver
Push Ay, H_Leader
Push Ay, H_Member
Dim RgeAy() As Range
RgeAy = RgeAyHdr(Ws, Ay)
RgeAy(0).Value = TripChiNm
RgeAy(1).Value = DriverTy
RgeAy(2).Value = Driver
RgeAy(3).Value = Leader
RgeAy(4).Value = Member
End Function

Private Function RgeAyHdr(Ws As Worksheet, Ay$()) As Range()
Dim Wb As Workbook, J%, Nm As Name, O() As Range
ReDim O(UBound(Ay))
Set Wb = Ws.Parent
For J = 0 To UBound(Ay)
    Set Nm = Wb.Names(Ay(J))
    Set O(J) = Nm.RefersToRange
Next
RgeAyHdr = O
End Function

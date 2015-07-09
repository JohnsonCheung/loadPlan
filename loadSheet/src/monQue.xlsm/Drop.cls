VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Drop"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit
Public OrdNo As String
Public Cus As String
Public Adr As String
Public Content As String
Public Qty As String
Public PagList As String
Public OrdBy As String
Public Contact As String
Public RmkNo As String
Public NoEr As Boolean
Public Ft$
Function Init(Fdr$, Fn$) As Drop
Ft = PthQue & Fdr & "\" & Fn
Dim S As TextStream
On Error GoTo X
Set S = FSO.OpenTextFile(Ft)
OrdNo = S.ReadLine ' 1
Cus = S.ReadLine   ' 2
Adr = S.ReadLine   ' 3
Content = S.ReadLine ' 4
Qty = S.ReadLine     ' 5
PagList = S.ReadLine ' 6
OrdBy = S.ReadLine   ' 7
Contact = S.ReadLine ' 8
RmkNo = S.ReadLine   ' 9
S.Close
NoEr = True
Set Init = Me
Exit Function
X:
ClsTextStream S
WrtLog vbTab & "Cannot read drop-??.txt  File name=[" & Ft & "].  ErMsg=[" & Err.Description & "]"
End Function
Function Fillin(Ws As Worksheet, Rno%, CnoAy%()) As Boolean
Dim RgeAy(8) As Range, J%
For J = 0 To UB(CnoAy)
    Set RgeAy(J) = Ws.Cells(Rno, CnoAy(J))
Next
RgeAy(0).Value = Replace(OrdNo, "\n", vbCrLf)
RgeAy(1).Value = Replace(Cus, "\n", vbCrLf)
RgeAy(2).Value = Replace(Adr, "\n", vbCrLf)
RgeAy(3).Value = Replace(Content, "\n", vbCrLf)
RgeAy(4).Value = Replace(Qty, "\n", vbCrLf)
RgeAy(5).Value = Replace(PagList, "\n", vbCrLf)
RgeAy(6).Value = Replace(OrdBy, "\n", vbCrLf)
RgeAy(7).Value = Replace(Contact, "\n", vbCrLf)
RgeAy(8).Value = Replace(RmkNo, "\n", vbCrLf)
End Function


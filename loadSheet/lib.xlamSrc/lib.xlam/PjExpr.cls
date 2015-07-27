VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "PjExpr"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Pj As Pj

Property Get Pth$()
Dim O$
Dim F$
    F = Pth__File

O = F & "Src\": If Not Fso.FolderExists(O) Then MkDir O
O = O & A_Pj.Fn & "\": If Not Fso.FolderExists(O) Then MkDir O
Pth = O
End Property

Private Function Pth__File$()
On Error GoTo X
Pth__File = A_Pj.A_Pj.Filename
X:
End Function

Function NewPjExpr(Pj As Pj) As PjExpr
Set A_Pj = Pj
Set NewPjExpr = Me
End Function

Function Export() As PjExpr
Dim P$
    P = Pth

If P <> "" Then
    PthDlt_File Me.Pth
    Dim A() As Md, J%
    A = A_Pj.MdAy
    For J = 0 To UB(A)
        A(J).Export
    Next
End If
Set Export = Me
End Function

Sub Brw()
PthOpn Pth
End Sub

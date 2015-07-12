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
O = A_Pj.Pth & "Src\": If Not Fso.FolderExists(O) Then MkDir O
O = O & A_Pj.Fn & "\": If Not Fso.FolderExists(O) Then MkDir O
Pth = O
End Property

Sub Init(Pj As Pj)
Set A_Pj = Pj
End Sub

Function Export() As PjExpr
PthDlt_File Me.Pth
Dim A() As Md, J%
A = A_Pj.MdAy
For J = 0 To UB(A)
    A(J).Export
Next
Set Export = Me
End Function

Sub Brw()
PthOpn Pth
End Sub

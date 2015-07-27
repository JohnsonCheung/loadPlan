VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Md"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = True
Option Explicit
Public A_Md As CodeModule
Sub Init(Md As CodeModule)
Set A_Md = Md
End Sub
Property Get Cmp() As VBComponent
If IsNoMd Then Exit Property
Set Cmp = A_Md.Parent
End Property
Property Get IsNoMd() As Boolean
IsNoMd = IsNothing(A_Md)
End Property
Property Get Nm$()
If IsNoMd Then Exit Function
Nm = A_Md.Parent.Name
End Property
Sub BrwExportSrc()
FtBrw Pj.Pth & Fn
End Sub
Property Get Pj() As Pj
Dim A As VBComponent, O As New Pj
Set A = Cmp
If IsNothing(A) Then Exit Function
Set Pj = O.NewPj(A.Collection.Parent)
End Property
Sub Export()
Cmp.Export Pj.Expr.Pth & Fn
End Sub
Function Fn$()
Fn = Nm & Ext
End Function
Function Ty() As vbext_ComponentType
Ty = Cmp.Type
End Function
Function Ext$()
Select Case Ty
Case vbext_ComponentType.vbext_ct_ClassModule: Ext = ".cls"
Case vbext_ComponentType.vbext_ct_StdModule: Ext = ".bas"
End Select
End Function

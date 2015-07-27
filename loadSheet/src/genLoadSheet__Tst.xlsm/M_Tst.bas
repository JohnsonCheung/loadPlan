Attribute VB_Name = "M_Tst"
Option Explicit
Public Const C_Seg2$ = "Trip-2015-01-01#002"
Public Const C_Seg1$ = "Trip-2015-01-01#001"
Private Sub TstAll()
If True Then
    TstAll_Rmk
Else
    TstAll_Rmk
    TstAll_RmkLinOfOrd
    TstAll_RmkLinOfAdr
    TstAll_RmkOfAdr
    TstAll_RmkOfOrd
    TstAll_Fdr
    TstAll_Hdr
    TstAll_Drop
    TstAll_DropLin
    TstAll_Log
    TstAll_LoadSheet
    TstAll_LSFfn
    TstAll_LSPth
    TstAll_Ws1
    TstAll_Ws2
End If
End Sub


Sub CpyQue()
CpyQue1
CpyQue2
End Sub

Sub CpyQue1()
If False Then
    Shell LSPth.Que & "CpyQue1.bat", vbHide
Else
    Dim ToPth$, FmPth$
    ToPth = LSPth.Que & C_Seg1
    FmPth = LSPth.Que & C_Seg1
    Fso.CopyFolder FmPth, ToPth
End If
End Sub

Sub CpyQue2()
If False Then
    Shell LSPth.Que & "CpyQue2.bat", vbHide
Else
    Dim ToPth$, FmPth$
    ToPth = LSPth.Que & C_Seg2
    FmPth = LSPth.Que & C_Seg2
    Fso.CopyFolder FmPth, ToPth
End If
End Sub

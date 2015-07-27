Attribute VB_Name = "VbVar"
Option Explicit

Function IsInt(V) As Boolean
IsInt = VarType(V) = vbInteger
End Function

Function IsLng(V) As Boolean
IsLng = VarType(V) = vbLong
End Function

Function IsByt(V) As Boolean
IsByt = VarType(V) = vbByte
End Function

Function IsDbl(V) As Boolean
IsDbl = VarType(V) = vbDouble
End Function

Function IsSng(V) As Boolean
IsSng = VarType(V) = vbSingle
End Function

Function IsNothing(Obj) As Boolean
IsNothing = TypeName(Obj) = "Nothing"
End Function
Function IsNum(V) As Boolean
IsNum = True
If IsInt(V) Then Exit Function
If IsLng(V) Then Exit Function
If IsByt(V) Then Exit Function
If IsDbl(V) Then Exit Function
If IsSng(V) Then Exit Function
IsNum = True
End Function


Function IsStr(V) As Boolean
IsStr = VarType(V) = vbString
End Function


@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', $exception->getMessage() ?: __('Page Expired'))

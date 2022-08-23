﻿<!DOCTYPE html>
<html mip="">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://c.mipcdn.com/static/v1/mip.css">
    <meta name="keywords" content="<sl-tag>网站关键字</sl-tag>">
    <meta name="description" content="<sl-tag>网站描述</sl-tag>">
    <title><sl-tag>网站标题</sl-tag></title>
<link href="<sl-tag>安装目录</sl-tag><sl-tag>网站ico</sl-tag>" rel="shortcut icon">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta name="applicable-device" content="pc,mobile">
    <meta name="MobileOptimized" content="width">
    <meta name="HandheldFriendly" content="true">
    <style mip-custom="">
        *{
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .clearfix{zoom:1;}
        .clearfix:before,.clearfix:after{display:table;line-height:0;content:"";}
        .clearfix:after{clear:both;}
        li {
            list-style-type: none;
        }
        .mip-banner {
            margin: 0 auto;
            position: relative;
            padding-top: 70px;
        }
        .mip-banner .mip-carousel-indicatorDot {
            display: inline-block!important;
        }
        .mip-banner .mip-carousel-preBtn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            top: 48%;
            cursor: pointer;
            left: 2%;
            opacity: .4;
        }
        .mip-banner .mip-carousel-preBtn:hover {
            opacity: 1;
        }
        .mip-banner .mip-carousel-nextBtn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            top: 48%;
            cursor: pointer;
            right: 2%;
            opacity: .4;
        }
        .mip-banner .mip-carousel-nextBtn:hover {
            opacity: 1;
        }
        .mip-carousel-indicator-wrapper {
            position: absolute;
            width: 100%;
            bottom: 10px;
        }
        .mip-banner .mip-carousel-indicatorDot .mip-carousel-indecator-item {
            width: 12px;
            height: 12px;
            background: #fff;
        }
        .mip-banner .mip-carousel-indicatorDot .mip-carousel-activeitem {
            background: #4e97d9;
        }
        .mip-top-head[type="top"], mip-fixed[type="bottom"] {
            width: 100%;
            overflow: visible;
        }
        .mip-center {
            width: 1200px;
            margin: 0 auto;
        }
        .mip-pcheader {
            height: 70px;
            width: 100%;
            z-index: 19;
            background: #fff;
            border: 1px solid #eee;
        }
        .mip-pcheader .mip-text {
            width: 100%;
            padding: 0;
        }
        .mip-logo {
            width: 25%;
            float: left;
            margin-top: 15px;
        }
        .mip-logo mip-img {
            height: 40px;
            width: 150px;
            display: block;
            transition: .5s;
        }
        .mip-logo mip-img:hover {
            transform: scale(1.1);
            transition: .5s;
        }
        .mip-nav {
            width: 75%;
            float: left;
            background: #fff;
        }
        mip-nav-slidedown #bs-navbar .navbar-nav {
            margin-right: 0;
        }
        .mip-nav #bs-navbar .navbar-nav li {
            text-align: center;
            position: relative;
        }
        .mip-nav #bs-navbar .navbar-nav li a {
            font-size: 14px;
            color: #666;
            margin: 0 15px;
            padding: 0;
            display: block;
        }
        .mip-nav #bs-navbar .navbar-nav li ul {
            padding-left: 0;
        }
        .mip-nav #bs-navbar .navbar-nav li ul li a {
            line-height: 30px;
            font-size: 14px;
            padding: 0 15px;
        }
        .mip-nav #bs-navbar .navbar-nav li ul li:first-child a {
            padding-top: 10px; 
        }
        .mip-nav #bs-navbar .navbar-nav li span {
            font-size: 14px;
            color: #2a333c;
            margin: 0 15px;
            padding: 0;
            display: block;
        }
        .mip-nav #bs-navbar li a:hover {
            color: #4e97d9;
        }
        .mip-mbtn {
            display: none;
            float: right;
            width: 44px;
            height: 44px;
            padding-top: 8px;
            position: absolute;
            right: 5px;
            z-index: 99;
        }
        .mip-mbtn span {
            width: 44px;
            height: 44px;
            display: block;
            background: url(<sl-tag>安装目录</sl-tag>pc/mip/images/other.png) no-repeat center;
            background-size: 100%;
            background-color: #4e97d9;
            border-radius: 10px;
        }
        .mip-mnav {
            width: 80%;
            background: #f3f3f3;
            position: relative;
            z-index: 100001;
        }
        .mip-mnav #left-sidebar {
            width: 100%;
        }
        .mip-mnav .MIP-SIDEBAR-MASK {
            z-index: 98;
        }
        .mip-mnav li {
            line-height: 40px;
            margin: 0 20px;
            border-bottom: 1px solid #ddd;
        }
        .mip-mnav li a {
            display: block;
        }
        .mip-box1 {
            width: 100%;
            padding: 50px 0;
            background: #f5f5f5;
        }
        .mip-boxtit {
            width: 100%;
        }
        .mip-boxtit h3 {
            font-size: 36px;
            display: block;
            text-align: center;
            color: #444;
            font-weight: 300;
        }
        .mip-boxtit span {
            font-size: 16px;
            display: block;
            text-align: center;
            color: #999;
            margin-top: 10px;
        }
        .mip-box1 mip-vd-tabs .mip-vd-tabs-nav {
            background: none;
            -webkit-justify-content: inherit;
            text-align: center;
            display: block;
            margin-top: 20px;
        }
        .mip-box1 mip-vd-tabs .mip-vd-tabs-nav-li {
            width: 116px!important;
            height: 36px;
            line-height: 36px;
            background: #fff;
            padding: 0;
            text-align: center;
            -webkit-box-flex:  inherit;
            -webkit-flex: inherit;
            margin: 0 5px;
            display: inline-block;
            cursor: pointer;
        }
        .mip-box1 mip-vd-tabs .mip-vd-tabs-nav .mip-vd-tabs-nav-selected{
            background: #62a8ea;
            color: #fff;
        }
        .mip-box1 .mip-list {
            width: 100%;
            margin-top: 20px;
        }
        .mip-box1 .mip-list li {
            width: 23%;
            margin: 0 1%;
            background: #fff;
            float: left;
            margin-bottom: 20px;
        }
        .mip-box1 .mip-list li mip-img {
            width: 100%;
            height: 264px;
            line-height: 264px;
            text-align: center;
        }
        .mip-box1 .mip-list li mip-img img {
            height: auto;
            vertical-align: middle;
            display: -webkit-inline-box;
            max-height: 100%;
            max-width: 100%;
            width: auto;
            min-width: auto;
            padding: 5px!important;
        }
        .mip-box1 .mip-list li span {
            line-height: 36px;
            display: block;
            font-size: 16px;
            color: #2a333c;
            font-weight: bold;
            text-align: center;
        }
        .mip-box1 .mip-list li:hover {
            
        }
        .mip-box1 .mip-list li span:hover {
            text-decoration: underline;
            color: #4e97d9;
        }
        .mip-more {
            width: 88px;
            height: 33px;
            line-height: 33px;
            margin: 0 auto;
            border: 1px solid #eee;
            margin-top: 30px;
        }
        .mip-more a {
            font-size: 14px;
            display: block;
            text-align: center;
            color: #999;
        }
        .mip-more:hover {
            border: 1px solid #4e97d9;
        }
        .mip-more:hover a{
            color: #4e97d9;
        }
        
        .mip-box3 {
            padding: 50px 0;
            width: 100%;
        }
        .mip-box3 .mip-list {
            width: 100%;
            margin-top: 30px;
        }
        .mip-box3 .mip-list li {
            width: 48%;
            margin: 0 1%;
            float: left;
            margin-bottom: 30px;
        }
        .mip-box3 .mip-list li .mip-fl {
            float: left;
            width: 30%;
        }
        .mip-box3 .mip-list li .mip-fl mip-img {
            max-width: 100%;
            display: block;
        }
        .mip-box3 .mip-list li .mip-fr {
            float: left;
            width: 70%;
            padding: 0 3%
        }
        .mip-box3 .mip-list li .mip-fr span {
            font-size: 18px;
            font-weight: 400;
            color: #2a333c;
            padding-top: 5px;
            display: block;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        .mip-box3 .mip-list li .mip-fr span:hover {
            color: #4e97d9;
        }
        .mip-box3 .mip-list li .mip-fr p {
            font-size: 14px;
            color: #999;
            line-height: 24px;
            padding-top: 10px;
        }
        .mip-box3 .mip-list li .mip-fr i {
            font-weight: 300;
            font-size: 14px;
            color: #797979;
            padding-top: 10px;
            font-style: normal;
            display: block;
        }
        
        .mip-box4 {
            padding: 50px 0;
            width: 100%;
            background: #fff;
        }
        .mip-box4 .mip-list {
            width: 100%;
            margin-top: 30px;
        }
        .mip-box4 .mip-list li {
            width: 31.33%;
            margin: 0 1%;
            float: left;
            margin-bottom: 30px;
            background: #eee;
            overflow: hidden;
        }
        .mip-box4 .mip-list li .mip-pic {
            overflow: hidden;
        }
        .mip-box4 .mip-list li mip-img {
            max-width: 100%;
            display: block;
            margin: 0 auto;
            transform: scale(1);
            transition: .5s;
        }
        .mip-box4 .mip-list li mip-img:hover {
            transform: scale(1.2);
            transition: .5s;
        }
        .mip-box4 .mip-list li span {
            font-size: 20px;
            line-height: 30px;
            margin: 15px 0;
            display: block;
            text-align: center;
            color: #2a333c;
        }
        .mip-box4 .mip-list li p {
            font-size: 14px;
            display: block;
            color: #5e7387;
            line-height: 22px;
            padding: 0 5%;
            text-align: center;
        }
        
        .mip-box2 {
            width: 100%;
            padding: 50px 0;
            background: #f5f5f5;
        }
        .mip-box2 .mip-cont {
            width: 100%;
            margin-top: 50px;
        }
        .mip-box2 .mip-fl {
            width: 68%;
            padding-right: 5%;
            float: left;
        }
        .mip-box2 .mip-fl h3 {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 18px;
        }
        .mip-box2 .mip-fl p {
            font-size: 14px;
            line-height: 28px;
            color: #5e7387;
        }
        .mip-box2 .mip-fl a {
            border: solid 1px #ddd;
            background: transparent;
            border-radius: 4px;
            font-size: 14px;
            padding: 6px 15px;
            margin: 0;
            display: inline-block;
            line-height: 20px;
            background: #eee;
            margin-top: 20px;
        }
        .mip-box2 .mip-fl a:hover {
            color: #62a8ea;
        }
        .mip-box2 .mip-fr {
            width: 32%;
            float: right;
        }
        .mip-box2 .mip-fr mip-img {
            display: block;
            max-width: 100%;
        }
        .mip-footer{
            background: #fff;
            width: 100%;
            padding: 20px 0;
            border-top: 1px solid #eee;
        }
        .mip-footer .mip-fr {
            float: left;
            width: 70%;
        }
        .mip-footer .mip-fr ul {
            width: 25%;
            float: left;
        }
        .mip-footer .mip-fr ul li {}
        .mip-footer .mip-fr li a {
            font-size: 14px;
            color: #5e7387;
            line-height: 28px;
            display: block;
        }
        .mip-footer .mip-fr li:first-child a {
            font-size: 18px;
            color: #2a333c;
            line-height: 40px;
            display: block;
        }
        .mip-footer .mip-fr li a:hover {
            color: #4e97d9;
            text-decoration: underline;
        }
        .mip-footer .mip-fr li:first-child a:hover {
            color: #2a333c;
            text-decoration: none;
        }
        .mip-footer .mip-fl {
            float: left;
            width: 30%;
            text-align: center;
        }
        .mip-footer .mip-fl p {
            color: #5e7387;
            font-size: 26px;
            margin-top: 15px;
        }
        .mip-footer .mip-fl span {
            color: #aaa;
            line-height: 150%;
            padding-top: 8px;
        }
        .mip-copyright {
            width: 100%;
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }
        .mip-copyright p {
            font-size: 14px;
            line-height: 30px;
            color: #999;
        }
        .mip-copyright p a {
            font-size: 14px;
            line-height: 30px;
            color: #999;
        }
        .mip-copyright span {
            font-size: 14px;
            line-height: 30px;
            color: #999;
            display: block;
        }
        .mip-copyright span a {
            font-size: 14px;
            line-height: 30px;
            color: #999;
            padding-right: 10px;
        }
        .mip-copyright span a:hover {
            color: #4e97d9;
            text-decoration: underline;
        }
        .mip-gototop-show {
            background-color: #89BCEB!important;
            background: url(<sl-tag>安装目录</sl-tag>pc/mip/images/top.png) no-repeat center;
            border: none;
        }
        .mip-footnav {
            height: 52px;
            width: 100%;
            background: #4e97d9;
            padding-top: 6px;
            display: none;
        }
        .mip-footnav li {
            width: 25%;
            float: left;
        }
        .mip-footnav li mip-img {
            display: block;
            margin: 0 auto;
            width: 23px;
            height: 23px;
        }
        .mip-footnav li span {
            display: block;
            text-align: center;
            font-size: 14px;
            color: #fff;
            padding-top: 2px;
        }
        .mip-nav mip-nav-slidedown #bs-navbar li:last-child .navbar-more:after {
            display: none;
        }
        .mip-box9 {
            width: 100%;
            padding: 40px 0;
        }
        .mip-box9 .mip-box {
            width: 100%;
            margin-top: 40px;
        }
        .mip-box9 .mip-box .mip-fl {
            width: 50%;
            float: left;
        }
        .mip-box9 .mip-box .mip-fl .mip-pic {
            width: 90%;
            border: 1px solid #ddd;
            overflow: hidden;
        }
        .mip-box9 .mip-box .mip-fl .mip-pic mip-img {
            transform: scale(1);
            transition: .5s;
        }
        .mip-box9 .mip-box .mip-fl .mip-pic:hover mip-img {
            transform: scale(1.1);
            transition: .5s;
        }
        .mip-box9 .mip-box .mip-fl .mip-text {
            width: 90%;
        }
        .mip-box9 .mip-box .mip-fl .mip-text .mip-time {
            float: left;
            padding-right: 20px;
            margin-right: 20px;
            background: url(<sl-tag>安装目录</sl-tag>pc/mip/images/shuri.gif) no-repeat right center;
        }
        .mip-box9 .mip-box .mip-fl .mip-text .mip-time p {
            display: block;
            font-size: 16px;
            color: #231815;
            line-height: 24px;
            float: none;
            text-align: center;
        }
        .mip-box9 .mip-box .mip-fl .mip-text .mip-time span {
            display: block;
            font-size: 30px;
            line-height: 100%;
            display: block;
            text-align: center;
        }
        .mip-box9 .mip-box .mip-fl .mip-text p {
            float: left;
            max-width: 80%;
        }
        .mip-box9 .mip-box .mip-fl .mip-text p a {
            font-size: 18px;
            color: #010101;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .mip-box9 .mip-box .mip-fl .mip-text p span {
            font-size: 14px;
            color: #9a9a9a;
            line-height: 22px;
            display: block;
            margin-top: 5px;
        }
        .mip-box9 .mip-box .mip-fr {
            width: 50%;
            float: left;
            border-left: 1px solid #ddd;
            padding-left: 5%;
        }
        .mip-box9 .mip-box .mip-fr section {
            width: 100%;
        }
        .mip-box9 .mip-box .mip-fr section li {
            font-size: 16px;
            height: 18px;
            color: #000;
            display: block;
            line-height: 100%;
            float: left;
            padding-right: 25px;
            margin-right: 25px;
            border-right: 2px solid #666;
            padding-left: 0;
            text-align: left;
            width: auto;
        }
        .mip-box9 .mip-box .mip-fr section a {
            float: left;
            height: 18px;
            font-size: 16px;
            color: #000;
            display: block;
            line-height: 100%;
            float: left;
        }
        .mip-box9 .mip-vd-tabs-nav {
            padding: 0;
            height: auto;
            display: block
        }
        .mip-box9 .mip-box .mip-fr section .mip-vd-tabs-nav-selected {
            font-weight: bold;
            border-bottom: none;
        }
        .mip-box9 .mip-box .mip-fr .mip-list {}
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text {}
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text p {
            max-width: 80%;
            float: left;
        }
        .mip-box9 .mip-box .mip-fr .mip-list li {
            float: left;
            margin-top: 20px;
        }
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text p a {
            font-size: 16px;
            color: #010101;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text p a:hover {
            color: #2c99ff;
        }
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text p span {
            font-size: 14px;
            color: #9a9a9a;
            line-height: 22px;
            display: block;
            margin-top: 5px;
        }
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text .mip-time {
            float: right;
            margin: 0;
            padding: 0;
            padding-left: 20px;
            margin-left: 20px;
            background: url(<sl-tag>安装目录</sl-tag>pc/mip/images/shuri.gif) no-repeat left center;
        }
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text .mip-time p {
            display: block;
            padding: 0;
            margin: 0;
            font-size: 16px;
            color: #231815;
            max-width: 100%;
            float: none;
            line-height: 24px;
            text-align: center;
        }
        .mip-box9 .mip-box .mip-fr .mip-list .mip-text .mip-time span {
            display: block;
            font-size: 30px;
            line-height: 100%;
            font-weight: 100;
            text-align: center;
        }
        @media screen and (max-width: 1200px) {
            .mip-center {
                width: 100%;
                padding: 0 .5%;
            }
        }
        @media screen and (max-width: 768px) {
            .mip-mbtn {
                display: block;
            }
            .mip-nav {
                display: none;
            }
            .mip-banner .mip-carousel-preBtn {
                display: none;
            }
            .mip-banner .mip-carousel-nextBtn {
                display: none;
            }
            .mip-logo {
                width: 40%;
            }
            .mip-carousel-indicator-wrapper {
                bottom: 2px;
            }
            .mip-banner .mip-carousel-indicatorDot .mip-carousel-indecator-item {
                width: 10px;
                height: 10px;
            }
            .mip-pcheader {
                height: 60px;
                padding: 0 2%;
                border-bottom: 1px solid #eee;
            }
            .mip-banner {
                padding-top: 60px;
            }
            .mip-logo {
                margin-top: 10px;
            }
            .mip-box1 .mip-list li {
                width: 48%;
                margin-bottom: 10px;
            }
            .mip-box3 .mip-list li {
                width: 98%;
            }
            .mip-box4 .mip-list li {
               
            }
            .mip-box2 .mip-cont {
                margin-top: 15px;
            }
            .mip-box2 .mip-fl {
                width: 100%;
                margin-top: 10px;
            }
            .mip-box2 .mip-fr {
                width: 100%;
            }
            .mip-box2 .mip-fr mip-img {
                max-width: 50%;
                margin: 0 auto;
            }
            .mip-footer .mip-fr {
                width: 100%;
                text-align: center;
            }
            .mip-footer .mip-fl {
                width: 100%;
            }
            .mip-top-head[type="top"], mip-fixed[type="bottom"] {
                height: 60px;
            }
            .mip-botfoot[type="top"], mip-fixed[type="bottom"] {
                height: auto;
            }
            .mip-box9 {
                padding: 10px 0;
            }
            .mip-box9 .mip-box .mip-fl {
                width: 100%;
            }
            .mip-box9 .mip-box .mip-fl .mip-pic {
                width: 100%;
            }
            .mip-box9 .mip-box .mip-fl .mip-text {
                width: 100%;
                display: none;
            }
            .mip-box9 .mip-box {
                margin-top: 15px;
            }
            .mip-box9 .mip-box .mip-fr {
                width: 100%;
                padding: 0 1%;
                margin-top: 0;
            }
            .mip-box9 .mip-box .mip-fr section {
                display: none;
            }
        }
        @media screen and (max-width: 450px) {
            .mip-box1 {
                padding: 20px 0;
            }
            .mip-boxtit span {
                font-size: 14px;
            }
            .mip-box1 .mip-list li mip-img {
                height: 12rem;
                line-height: 12rem;
            }
            .mip-box1 .mip-list li span {
                line-height: 36px;
            }
            .mip-box3 .mip-list li .mip-fl {
                width: 35%;
            }
            .mip-box3 .mip-list li .mip-fr {
                width: 65%;
                padding: 0 2%;
            }
            .mip-box3 .mip-list li .mip-fr span {
                font-size: 16px;
                padding-top: 1px;
            }
            .mip-box3 .mip-list li .mip-fr p {
                padding-top: 6px;
                font-size: 14px;
                line-height: 22px;
            }
            .mip-box3 {
                padding: 20px 0;
            }
            .mip-box4 {
                padding: 20px 0;
            }
            .mip-more {
                margin-top: 10px; 
            }
            .mip-box4 .mip-list li p {
                font-size: 13px;
                line-height: 22px;
            }
            .mip-box2 {
                padding: 20px 0;
            }
            .mip-box2 .mip-fr mip-img {
                max-width: 100%;
                margin: 0 auto;
            }
            .mip-boxtit h3 {
                font-size: 26px;
            }
            .mip-box1 mip-vd-tabs .mip-vd-tabs-nav {
                padding: 0 5px;
            }
            .mip-box1 mip-vd-tabs .mip-vd-tabs-nav-li {
                width: 23%!important;
                margin: 0 1%;
            }
            .mip-box3 .mip-list li .mip-fr i {
                padding-top: 5px;
            }
            .mip-box2 .mip-fl h3 {
                margin-bottom: 8px;
            }
            .mip-copyright {
                padding-bottom: 62px;
            }
            .mip-footnav {
                display: block;
            }
            .mip-box4 .mip-list li span {
                font-size: 14px;
                margin: 2px 0;
            }
            .mip-box9 .mip-box .mip-fr .mip-list .mip-text .mip-time {
                padding-left: 10px;
                margin-left: 0;
            }
        }
        @media screen and (max-width: 350px) {
            .mip-box1 .mip-list li mip-img {
                height: 10rem;
                line-height: 10rem;
            }
            .mip-box3 .mip-list li .mip-fl {
                width: 100%;
            }
            .mip-box3 .mip-list li .mip-fr {
                width: 100%;
                padding: 0 1%;
            }
            .mip-box4 .mip-list li {
                width: 100%;
            }
        }
    </style>
</head>
<body>
        <sl-tag>网站顶部</sl-tag>
    <div class="mip-banner clearfix">
        <mip-carousel autoplay="" defer="5000" layout="responsive" width="1920" height="670" buttoncontroller="" indicatorid="mip-carousel-example">
<sl-function f="getslide">
<parameter><![CDATA[<mip-img src="<sl-tag>安装目录</sl-tag>%图片路径%"></mip-img>]]></parameter>
</sl-function>
</mip-carousel>
        <div class="mip-carousel-indicator-wrapper">
            <div class="mip-carousel-indicatorDot" id="mip-carousel-example">
                <div class="mip-carousel-activeitem mip-carousel-indecator-item"></div>
                <div class="mip-carousel-indecator-item"></div>
                <div class="mip-carousel-indecator-item"></div>
            </div>
        </div>
    </div>
    <div class="mip-box4 clearfix">
        <div class="mip-center">
            <div class="mip-boxtit">
                <h3>产品分类</h3>
                <span>Class</span>
            </div>
            <div class="mip-list clearfix">
                <ul>
<sl-function f="product_sort_list">
<parameter><![CDATA[<li>                                                    <a href="%产品分类链接%" title="%产品分类标题%" data-type="mip">                                <div class="mip-pic"><mip-img class="mip-img1" src="<sl-tag>安装目录</sl-tag>%产品分类图片%"></mip-img></div>                                <span>%产品分类标题%</span>                            </a>                                            </li>]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>
</ul>
            </div>
        </div>
    </div>
    
    <div class="mip-box1 clearfix">
        <div class="mip-center">
            <div class="mip-boxtit">
                <h3>产品展示</h3>
                <span>Product</span>
            </div>
            <mip-vd-tabs>
                <section>
<sl-function f="product_sort_list">
<parameter><![CDATA[<li>%产品分类标题%</li>]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>
</section>
              
              <sl-function f="product_listx">
<parameter><![CDATA[<div class="mip-list clearfix">                        <ul>%产品列表%</ul></div>]]></parameter>
<parameter><![CDATA[<li>                                <a href="%产品链接%" data-type="mip">                                    <mip-img src="<sl-tag>安装目录</sl-tag>%产品小图%"></mip-img>                                    <span>%产品标题%</span>                                </a>                            </li>]]></parameter>
<parameter><![CDATA[8]]></parameter>
<parameter><![CDATA[0]]></parameter>
</sl-function>
              
                                    
                            </mip-vd-tabs>
        </div>
    </div>
    <div class="mip-box3 clearfix">
        <div class="mip-center">
            <div class="mip-boxtit">
                <h3>新闻资讯</h3>
                <span>News</span>
            </div>
            <div class="mip-list clearfix">
                <ul>
<sl-function f="news_list2">
<parameter><![CDATA[]]></parameter>
<parameter><![CDATA[<li>                            <div class="mip-fl">                                <a href="%新闻链接%" title="%新闻标题%" data-type="mip"><mip-img src="<sl-tag>安装目录</sl-tag>%新闻图片%"></mip-img></a>                            </div>                            <div class="mip-fr">                                <a href="%新闻链接%" title="%新闻标题%" data-type="mip"><span>%新闻标题%</span></a>                                <p>%新闻简述%</p>                                <i>%发表日期%</i>                            </div>                        </li>]]></parameter>
<parameter><![CDATA[4]]></parameter>
<parameter><![CDATA[0]]></parameter>
<parameter><![CDATA[normal]]></parameter>
</sl-function>
</ul>
            </div>
        </div>
    </div>
    
    <sl-function f="text_intro">
<parameter><![CDATA[<div class="mip-box2 clearfix">        <div class="mip-center">            <div class="mip-boxtit">                <h3>%简介标题%</h3>                <span>%简介英文标题%</span>            </div>            <div class="mip-cont clearfix">                                    <div class="mip-fr">                        <mip-img src="<sl-tag>安装目录</sl-tag>%简介图片%"></mip-img>                    </div>                    <div class="mip-fl">                                                <p></p><p>%简介内容%</p><p></p>                        <a href="%简介链接%" title="%简介标题%" data-type="mip">更多>></a>                    </div>                            </div>        </div>    </div>]]></parameter>
<parameter><![CDATA[a]]></parameter>
<parameter><![CDATA[200]]></parameter>
</sl-function>
    
    <sl-tag>网站底部</sl-tag>
</body>
</html>
// pages/index/index.js
Page({

      navigateBack: function() {
        wx.navigateTo({
          title: "goback",
          url: '../collection/collection'
        })
      },
      Back: function() {
        wx.navigateTo({
          title: "goback",
          url: '../history/history'
        })
      }})
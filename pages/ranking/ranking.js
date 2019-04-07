Page({ 
  /**
   * 页面的初始数据
   */
  data: {
    showView: true,
    showView1: false,
    bg: true,
    sview: false,
    view: true,
  },
  // 点击按钮切换榜单
  showButton: function () {
    var that = this;
    that.setData({
      showView: true,
      showView1: false,
      bg: true,
    })
  },
  showButton1: function () {
    var that = this;
    that.setData({
      showView: false,
      showView1: true,
      bg: false,
    })
  },

  onLoad:function(options){
    var that=this
    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/agent_company_rank',
      method:"get",
      header:{
        "content-type": "application/x-www-form-urlencoded" // 默认值

      },
      success:function(obj){
        console.log(obj);
        that.setData({
          rank1_type: obj.data.msg[0].agent_comp,
          rank2_type: obj.data.msg[1].agent_comp,
          rank3_type: obj.data.msg[2].agent_comp,
          rank4_type: obj.data.msg[3].agent_comp,
          rank5_type: obj.data.msg[4].agent_comp,
          rank6_type: obj.data.msg[5].agent_comp,
          rank7_type: obj.data.msg[6].agent_comp,
          rank8_type: obj.data.msg[7].agent_comp,
          rank9_type: obj.data.msg[8].agent_comp,
          rank10_type: obj.data.msg[9].agent_comp,
          rank1: obj.data.msg[0].agent_comp_num,
          rank2: obj.data.msg[1].agent_comp_num,
          rank3: obj.data.msg[2].agent_comp_num,
          rank4: obj.data.msg[3].agent_comp_num,
          rank5: obj.data.msg[4].agent_comp_num,
          rank6: obj.data.msg[5].agent_comp_num,
          rank7: obj.data.msg[6].agent_comp_num,
          rank8: obj.data.msg[7].agent_comp_num,
          rank9: obj.data.msg[8].agent_comp_num,
          rank10: obj.data.msg[9].agent_comp_num,
        })
      }

    })

    wx.request({
      url: 'https://m.ctrltab.xyz/bid_info/searchbid_views_rank',
      method: "get",
      header: {
        "content-type": "application/x-www-form-urlencoded" // 默认值

      },
      success: function (obj) {
        console.log(obj);
        that.setData({
          news1_type: obj.data.msg[0].b_title,
          news2_type: obj.data.msg[1].b_title,
          news3_type: obj.data.msg[2].b_title,
          news4_type: obj.data.msg[3].b_title,
          news5_type: obj.data.msg[4].b_title,
          news6_type: obj.data.msg[5].b_title,
          news7_type: obj.data.msg[6].b_title,
          news8_type: obj.data.msg[7].b_title,
          news9_type: obj.data.msg[8].b_title,
          news10_type: obj.data.msg[9].b_title,
          news1: obj.data.msg[0].views,
          news2: obj.data.msg[1].views,
          news3: obj.data.msg[2].views,
          news4: obj.data.msg[3].views,
          news5: obj.data.msg[4].views,
          news6: obj.data.msg[5].views,
          news7: obj.data.msg[6].views,
          news8: obj.data.msg[7].views,
          news9: obj.data.msg[8].views,
          news10: obj.data.msg[9].views,
        })
      }
    })

  }
})
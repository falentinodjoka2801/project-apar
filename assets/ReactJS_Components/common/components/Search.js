class Search extends React.Component{
     state     =    {
          searchStr :    '',
          submitted :    false,
          liveSearch     :    false
     }
     keyPress  =    (e)  =>   {
          let tP         =    this.props;
          let tS         =    this.state;
          let value      =    e.target.value;
          let keyCode    =    e.which;

          this.setState({searchStr : e.target.value});
          
          if(tS.liveSearch){
               tP.doSearch(value);
               this.setState({submitted : true});
          }else{
               if(keyCode === 13){
                    tP.doSearch(value);
                    this.setState({submitted : true});
               }
          }
     }
     render    =    ()   =>   {
          let tS    =    this.state;
          let tP    =    this.props;

          return(
               <div className={`input-group input-group-${(tP.size !== '')? tP.size : 'md'}`}  
                    style={{background: (tP.background === false)? '' : 'rgb(222, 225, 228)', padding: (tP.background === false)? '' : '5px 30px', borderRadius: '25px'}}>
                    <input type="text" placeholder={tP.placeholder} 
                         className="form-control" value={tS.searchStr} onChange={(e) => this.keyPress(e)} 
                         onKeyPress={(e) => this.keyPress(e)} />
                    <div className="input-group-append">
                         {tS.submitted && 
                              <span className="input-group-text">
                                   <span className={`material-icons cp text-danger`} style={{fontSize:'12pt'}}
                                        onClick={() => {
                                             tP.doSearch('');
                                             this.setState({searchStr : '', submitted : false});
                                        }}>close</span>
                              </span>
                         }
                         <span className="input-group-text">
                              <span className={`material-icons cp text-info`} style={{fontSize:'12pt'}}
                                   onClick={() => tP.doSearch(tS.searchStr)}>search</span>
                         </span>
                         <div className={'input-group-text'}>
                              <span className={'dropdown-toggle text-black'} data-toggle={'dropdown'}>
                                   Live Search Options ({(tS.liveSearch)? 'ON' : 'OFF'})
                              </span>
                              <div className={'dropdown-menu dropdown-menu-right'}>
                                   <li className={'dropdown-item text-success'} 
                                        onClick={() => this.setState({liveSearch : true})}>Turn On Live Search</li>
                                   <li className={'dropdown-item text-danger'} 
                                        onClick={() => this.setState({liveSearch : false})}>Turn Off Live Search</li>
                              </div>
                         </div>
                    </div>
               </div>
          );
     }
}

Search.defaultProps      =    {
     placeholder    :    'Search Component',
     doSearch       :    () => {},
     background     :    false,
     size           :    'sm'
}
class ButtonGroupCustom extends React.Component{
     render    =    ()   =>   {
          let tP              =    this.props;
          let buttons         =    tP.data;
          let activeButton    =    tP.active;
          let theme, themeColor;

          let ListButtons     =    buttons.map((button, index) => {
               themeColor     =    (typeof button.theme === 'undefined')? 'primary' : button.theme;
               theme          =    (button.key === activeButton)? themeColor : 'white';

               return    <button key={index} className={`btn btn-${theme}`}
                              onClick={() => tP.setActiveButton(button.key)}>{button.value}</button>
          });

          return(
               <div className={'button-group-custom'}>
                    {tP.label.length >= 1 &&
                         <label className={'d-block'} style={{fontSize:'10.5pt'}}>{tP.label}</label>
                    }
                    <div className="pl-0 pr-0 w-100 btn-group">
                         {ListButtons}
                    </div>
               </div>
          );
     }
}

ButtonGroupCustom.defaultProps      =    {
     componentValue :    ()   =>   {},
     label     :    ''
}
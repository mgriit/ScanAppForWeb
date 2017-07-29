using Microsoft.Owin;
using Owin;

[assembly: OwinStartupAttribute(typeof(DemoProject.Startup))]
namespace DemoProject
{
    public partial class Startup
    {
        public void Configuration(IAppBuilder app)
        {
            ConfigureAuth(app);
        }
    }
}

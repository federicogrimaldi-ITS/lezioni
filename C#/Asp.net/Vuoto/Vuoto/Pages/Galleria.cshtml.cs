using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

namespace Vuoto.Pages
{
    public class GalleriaModel : PageModel
    {
        public void OnGet()
        {
            @ViewData["Galleria"] = 0;
        }
    }
}

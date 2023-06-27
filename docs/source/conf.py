# Configuration file for the Sphinx documentation builder.
# Read https://www.sphinx-doc.org/en/master/usage/configuration.html for more options available

import sphinx_pdj_theme

# -- Project information

project = 'IP2Location.io PHP SDK'
copyright = '2023, IP2Location'
author = 'IP2Location'

release = '1.0.0'
version = '1.0.0'

# -- General configuration

extensions = [
    'sphinx.ext.duration',
    'sphinx.ext.doctest',
    # 'sphinx.ext.autodoc',
    # 'sphinx.ext.autosummary',
    # 'sphinx.ext.intersphinx',
    'myst_parser',
    'sphinx_copybutton',
]

# https://myst-parser.readthedocs.io/en/latest/syntax/optional.html

myst_enable_extensions = [
    # "amsmath",
    # "attrs_inline",
    "colon_fence",
    "deflist",
    # "dollarmath",
    "fieldlist",
    # "html_admonition",
    # "html_image",
    # "linkify",
    # "replacements",
    # "smartquotes",
    # "strikethrough",
    # "substitution",
    # "tasklist",
]


# templates_path = ['_templates']

# -- Options for HTML output

# html_theme = 'renku'
# html_theme = 'sphinx_rtd_theme'
html_theme = 'sphinx_pdj_theme'
html_theme_path = [sphinx_pdj_theme.get_html_theme_path()]

# PDJ theme options, see the list of available options here: https://github.com/jucacrispim/sphinx_pdj_theme/blob/master/sphinx_pdj_theme/theme.conf
html_theme_options = {
    # Hide the name of the project above the search bar
    'home_link': 'hide'
}

# The name of an image file (relative to this directory) to place at the top
# of the sidebar.
html_logo = 'images/android-chrome-512x512.png'

# Favicon
html_favicon = 'images/favicon-32x32.png'

html_title = "IP2Location.io PHP SDK"